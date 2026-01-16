<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleData;
use Alizharb\FilamentModuleManager\Data\ModuleInstallResultData;
use Alizharb\FilamentModuleManager\Exceptions\ModuleNotFoundException;
use Alizharb\FilamentModuleManager\Models\Module;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Throwable;
use ZipArchive;

/**
 * Service class to handle module management operations.
 */
class ModuleManagerService
{
    public function __construct(
        private ?ModuleDependencyService $dependencyService = null,
        private ?ModuleBackupService $backupService = null,
        private ?AuditLogService $auditService = null,
        private ?ModuleHealthService $healthService = null,
        private ?ModuleUpdateService $updateService = null
    ) {
        $this->dependencyService = $dependencyService ?? app(ModuleDependencyService::class);
        $this->backupService = $backupService ?? app(ModuleBackupService::class);
        $this->auditService = $auditService ?? app(AuditLogService::class);
        $this->healthService = $healthService ?? app(ModuleHealthService::class);
        $this->updateService = $updateService ?? app(ModuleUpdateService::class);
    }

    /**
     * Enable a module.
     *
     * @throws ModuleNotFoundException
     */
    public function enable(string $moduleName): ?ModuleData
    {
        if (! ModuleFacade::has($moduleName)) {
            throw new ModuleNotFoundException($moduleName);
        }

        return $this->toggleModuleStatus($moduleName, true);
    }

    /**
     * Disable a module.
     *
     * @throws ModuleNotFoundException
     */
    public function disable(string $moduleName): ?ModuleData
    {
        if (! ModuleFacade::has($moduleName)) {
            throw new ModuleNotFoundException($moduleName);
        }

        return $this->toggleModuleStatus($moduleName, false);
    }

    /**
     * Enable or disable a module.
     *
     * @param  string  $moduleName  The module's folder or name.
     * @param  bool  $enable  True to enable, false to disable.
     * @return ModuleData|null Returns module data if successful, null otherwise.
     */
    public function toggleModuleStatus(string $moduleName, bool $enable): ?ModuleData
    {
        if (! ModuleFacade::has($moduleName)) {
            Log::warning("Module '{$moduleName}' not found.");
            $this->auditService?->log($enable ? 'enable' : 'disable', $moduleName, false, null, 'Module not found');

            return null;
        }

        // Check dependencies before disabling
        if (! $enable && ! $this->canDisable($moduleName)) {
            $dependents = $this->dependencyService?->getDependents($moduleName) ?? [];
            Log::warning("Module '{$moduleName}' cannot be disabled. Required by: ".implode(', ', $dependents));
            $this->auditService?->log('disable', $moduleName, false, ['dependents' => $dependents], 'Module has active dependents');

            return null;
        }

        $enable ? ModuleFacade::enable($moduleName) : ModuleFacade::disable($moduleName);

        Artisan::call('optimize:clear');

        // Audit log
        $this->auditService?->log($enable ? 'enable' : 'disable', $moduleName, true);

        // Health check if enabled
        if (config('filament-module-manager.health_checks.auto_check')) {
            $this->healthService?->checkHealth($moduleName);
        }

        return Module::findData($moduleName);
    }

    /**
     * Install one or more modules from a ZIP file.
     *
     * @param  string  $relativeZipPath  Relative or absolute path to the ZIP file.
     * @param  bool  $isAbsolute  Set true if the path is absolute.
     */
    public function installModulesFromZip(string $relativeZipPath, bool $isAbsolute = false): ModuleInstallResultData
    {
        $fullPath = $isAbsolute ? $relativeZipPath : storage_path("app/public/{$relativeZipPath}");
        $modulesPath = base_path('Modules');

        if (! File::exists($fullPath)) {
            Log::error("ZIP file not found: {$fullPath}");

            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $zip = new ZipArchive;
        if ($zip->open($fullPath) !== true) {
            Log::error("Cannot open ZIP file: {$fullPath}");

            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $tempExtractPath = storage_path('app/temp_module_extract');
        File::ensureDirectoryExists($tempExtractPath);
        File::cleanDirectory($tempExtractPath);

        if (! $this->extractZipSafely($zip, $tempExtractPath)) {
            $zip->close();
            File::delete($fullPath);
            Log::error("ZIP extraction failed: {$fullPath}");

            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $zip->close();

        $entries = collect(File::directories($tempExtractPath))
            ->map(fn ($path) => basename($path))
            ->values();

        $files = collect(File::files($tempExtractPath))
            ->map(fn ($file) => $file->getFilename());

        $entries = $entries->merge($files);

        if ($entries->isEmpty()) {
            File::deleteDirectory($tempExtractPath);
            File::delete($fullPath);
            Log::error("ZIP is empty after extraction: {$fullPath}");

            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $movedModules = [];
        $skippedModules = [];

        DB::beginTransaction();
        try {
            foreach ($entries as $entry) {
                $sourcePath = "{$tempExtractPath}/{$entry}";
                $moduleName = File::isDirectory($sourcePath) ? $entry : pathinfo($entry, PATHINFO_FILENAME);

                // Check module.json to rename module folder
                $moduleJsonPath = "{$sourcePath}/module.json";
                if (File::exists($moduleJsonPath)) {
                    try {
                        $moduleConfig = json_decode(File::get($moduleJsonPath), true, 512, JSON_THROW_ON_ERROR);
                        if (! empty($moduleConfig['name'])) {
                            $moduleName = $moduleConfig['name'];
                        }
                    } catch (Throwable $e) {
                        Log::warning("Invalid module.json in {$sourcePath}: {$e->getMessage()}");
                    }
                }

                $destination = "{$modulesPath}/{$moduleName}";

                // Skip if already exists
                if (File::exists($destination)) {
                    $skippedModules[] = $moduleName;

                    continue;
                }

                // Move directory or single file
                if (File::isDirectory($sourcePath)) {
                    File::moveDirectory($sourcePath, $destination);
                } else {
                    File::ensureDirectoryExists($destination);
                    File::move($sourcePath, "{$destination}/{$entry}");
                }

                // Validate module
                if ($this->isValidModule($moduleName)) {
                    $movedModules[] = $moduleName;
                } else {
                    File::deleteDirectory($destination);
                    $skippedModules[] = $moduleName;
                }
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Module installation failed: {$e->getMessage()}");
        }

        ModuleFacade::scan();

        $installedModules = [];
        foreach ($movedModules as $moduleName) {
            if (ModuleFacade::has($moduleName)) {
                ModuleFacade::enable($moduleName);
                $installedModules[] = Module::findData($moduleName);
            } else {
                $modulePath = $this->getPathToModule($moduleName);
                $moduleJsonPath = "{$modulePath}/module.json";
                $moduleNameFromJson = $moduleName;

                if (File::exists($moduleJsonPath)) {
                    try {
                        $moduleConfig = json_decode(File::get($moduleJsonPath), true, 512, JSON_THROW_ON_ERROR);
                        if (! empty($moduleConfig['name'])) {
                            $moduleNameFromJson = $moduleConfig['name'];
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Invalid module.json for {$moduleName}: {$e->getMessage()}");
                    }
                }

                $installedModules[] = Module::findData($moduleName) ?? new \Alizharb\FilamentModuleManager\Data\ModuleData(
                    name: $moduleNameFromJson,
                    alias: Str::lower($moduleNameFromJson),
                    description: null,
                    active: false,
                    path: $this->getPathToModule($moduleName),
                    version: null,
                    authors: null,
                );
            }
        }

        File::deleteDirectory($tempExtractPath);
        File::delete($fullPath);

        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');

        return new ModuleInstallResultData(
            installed: $installedModules,
            skipped: $skippedModules
        );
    }

    /**
     * Install module from a GitHub repository.
     *
     * @param  string  $repo  User/repo or full URL.
     * @param  string  $branch  Branch name, defaults to 'main'.
     */
    public function installModuleFromGitHub(string $repo, string $branch = 'main'): ModuleInstallResultData
    {
        $tempPath = storage_path('app/temp_github_module');
        File::ensureDirectoryExists($tempPath);
        File::cleanDirectory($tempPath);

        if (! str_contains($repo, 'github.com')) {
            $repo = "https://github.com/{$repo}";
        }

        $branchesToTry = [$branch, 'master'];
        $zipDownloaded = false;
        $zipPath = "{$tempPath}/repo.zip";

        foreach ($branchesToTry as $b) {
            $zipUrl = rtrim($repo, '/')."/archive/refs/heads/{$b}.zip";
            try {
                $content = @file_get_contents($zipUrl);
                if ($content !== false) {
                    file_put_contents($zipPath, $content);
                    $zipDownloaded = true;
                    break;
                }
            } catch (Throwable $e) {
                Log::warning("Failed to download branch {$b} for {$repo}: {$e->getMessage()}");
            }
        }

        if (! $zipDownloaded) {
            Log::error("Failed to download any branch for GitHub repo: {$repo}");

            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        return $this->installModulesFromZip($zipPath, true);
    }

    /**
     * Install module from local path.
     *
     * @param  string  $path  Absolute or relative path.
     */
    public function installModuleFromPath(string $path): ModuleInstallResultData
    {
        $modulesPath = base_path('Modules');
        $moduleName = basename($path);

        // Try to read module name from module.json
        $moduleJsonPath = "{$path}/module.json";
        if (File::exists($moduleJsonPath)) {
            try {
                $moduleConfig = json_decode(File::get($moduleJsonPath), true, 512, JSON_THROW_ON_ERROR);
                if (! empty($moduleConfig['name'])) {
                    $moduleName = $moduleConfig['name'];
                }
            } catch (Throwable $e) {
                Log::warning("Invalid module.json in {$path}: {$e->getMessage()}");
            }
        }

        $destination = "{$modulesPath}/{$moduleName}";

        if (File::exists($destination)) {
            return new ModuleInstallResultData(installed: [], skipped: [$moduleName]);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destination);
        } else {
            File::ensureDirectoryExists($destination);
            File::copy($path, "{$destination}/".basename($path));
        }

        if ($this->isValidModule($moduleName)) {
            ModuleFacade::scan();
            ModuleFacade::enable($moduleName);

            return new ModuleInstallResultData(
                installed: [Module::findData($moduleName)],
                skipped: []
            );
        }

        return new ModuleInstallResultData(installed: [], skipped: [$moduleName]);
    }

    /**
     * Uninstall a module by removing its directory.
     *
     * @param  string  $moduleName  The module name.
     * @return bool True if uninstalled successfully, false otherwise.
     */
    public function uninstallModule(string $moduleName): bool
    {
        if (! $this->canUninstall($moduleName)) {
            Log::warning("Module '{$moduleName}' cannot be uninstalled.");
            $this->auditService?->log('uninstall', $moduleName, false, null, 'Module cannot be uninstalled');

            return false;
        }

        $module = ModuleFacade::find($moduleName);

        if (! $module) {
            Log::error("Module '{$moduleName}' not found for uninstallation.");
            $this->auditService?->log('uninstall', $moduleName, false, null, 'Module not found');

            return false;
        }

        try {
            // Create backup before uninstall if enabled
            if (config('filament-module-manager.backups.enabled') && config('filament-module-manager.backups.backup_before_uninstall')) {
                try {
                    $this->backupService?->createBackup($moduleName, 'Before uninstall');
                } catch (\Throwable $e) {
                    // Log warning but don't block uninstall if backup fails
                    Log::warning("Failed to create backup before uninstalling '{$moduleName}': {$e->getMessage()}");
                }
            }

            $modulePath = $module->getPath();

            if (File::deleteDirectory($modulePath)) {
                Artisan::call('optimize:clear');
                $this->auditService?->log('uninstall', $moduleName, true);

                return true;
            }

            $this->auditService?->log('uninstall', $moduleName, false, null, 'Failed to delete directory');

            return false;
        } catch (Throwable $e) {
            Log::error("Failed to uninstall module '{$moduleName}': {$e->getMessage()}");
            $this->auditService?->log('uninstall', $moduleName, false, null, $e->getMessage());

            return false;
        }
    }

    /**
     * Determine if a module can be disabled.
     */
    public function canDisable(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);

        // Check if module is protected
        if ($config['protected'] ?? false) {
            return false;
        }

        // Check if other modules depend on this one
        if ($this->dependencyService && ! $this->dependencyService->canDisable($moduleName)) {
            return false;
        }

        return true;
    }

    /**
     * Check if a module can be uninstalled.
     */
    public function canUninstall(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);

        // Check if module is protected
        if ($config['protected'] ?? false) {
            return false;
        }

        // Check if other modules depend on this one
        try {
            if ($this->dependencyService) {
                $this->dependencyService->canUninstall($moduleName);
            }
        } catch (\Throwable $e) {
            Log::warning("Cannot uninstall '{$moduleName}': {$e->getMessage()}");

            return false;
        }

        return true;
    }

    /**
     * Get module.json configuration.
     */
    protected function getModuleConfig(string $moduleName): array
    {
        $modulePath = $this->getPathToModule($moduleName);
        $path = "{$modulePath}/module.json";
        if (! File::exists($path)) {
            return [];
        }

        try {
            return json_decode(File::get($path), true, 512, JSON_THROW_ON_ERROR) ?: [];
        } catch (Throwable $e) {
            Log::warning("Invalid module.json for '{$moduleName}': {$e->getMessage()}");

            return [];
        }
    }

    /**
     * Validate if a folder is a module.
     */
    protected function isValidModule(string $folder): bool
    {
        $base = $this->getPathToModule($folder);

        if (! File::isDirectory($base)) {
            return false;
        }

        if (File::exists("{$base}/module.json") || File::exists("{$base}/composer.json")) {
            return true;
        }

        $phpFiles = collect(File::allFiles($base))
            ->filter(fn ($file) => $file->getExtension() === 'php');

        return $phpFiles->isNotEmpty();
    }

    /**
     * Check if a module exists.
     */
    protected function moduleExists(string $moduleName): bool
    {
        return ModuleFacade::has($moduleName) || File::isDirectory($this->getPathToModule($moduleName));
    }

    /**
     * Safely extract a ZIP archive.
     */
    public function installDependencies(string $moduleName): bool
    {
        $modulePath = $this->getPathToModule($moduleName);
        if (! File::exists("{$modulePath}/composer.json")) {
            return true; // No dependencies to install
        }

        // Use shell_exec or Process to run composer.
        // Warning: Running composer via web request can be slow or blocked.
        // It's better to use a Queue job, but for this plugin we might do it synchronously or via Artisan if possible.
        // Artisan doesn't have a 'composer' command by default.
        // We will try to find composer executable.

        try {
            $command = "cd {$modulePath} && composer install --no-interaction --prefer-dist --optimize-autoloader 2>&1";
            // Basic implementation:
            $output = shell_exec($command);
            Log::info("Composer install output for {$moduleName}: ".$output);

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to install dependencies for {$moduleName}: {$e->getMessage()}");

            return false;
        }
    }

    public function runMigrations(string $moduleName): bool
    {
        try {
            Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
            $this->auditService?->log('migrate', $moduleName, true);

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to migrate {$moduleName}: {$e->getMessage()}");
            $this->auditService?->log('migrate', $moduleName, false, null, $e->getMessage());

            return false;
        }
    }

    public function runSeeds(string $moduleName): bool
    {
        try {
            Artisan::call('module:seed', ['module' => $moduleName, '--force' => true]);
            $this->auditService?->log('seed', $moduleName, true);

            return true;
        } catch (\Throwable $e) {
            Log::error("Failed to seed {$moduleName}: {$e->getMessage()}");
            $this->auditService?->log('seed', $moduleName, false, null, $e->getMessage());

            return false;
        }
    }

    public function getPathToModule(string $moduleName): string
    {
        $modulesBasePath = config('modules.paths.modules', base_path('Modules'));

        return "{$modulesBasePath}/{$moduleName}";
    }

    /**
     * Safely extract a ZIP archive.
     */
    protected function extractZipSafely(ZipArchive $zip, string $extractTo): bool
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (Str::contains($filename, ['..', './', '\\'])) {
                Log::warning("ZIP contains invalid filename: {$filename}");

                return false;
            }
        }

        return $zip->extractTo($extractTo);
    }
}
