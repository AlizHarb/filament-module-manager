<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleData;
use Alizharb\FilamentModuleManager\Data\ModuleInstallResultData;
use Alizharb\FilamentModuleManager\Models\Module;
use Illuminate\Support\Facades\{Artisan, DB, File, Log};
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use ZipArchive;
use Throwable;

/**
 * Service class to handle module management operations.
 */
class ModuleManagerService
{
    /**
     * Enable or disable a module.
     *
     * @param string $moduleName The module's folder or name.
     * @param bool $enable True to enable, false to disable.
     * @return ModuleData|null Returns module data if successful, null otherwise.
     */
    public function toggleModuleStatus(string $moduleName, bool $enable): ?ModuleData
    {
        if (!ModuleFacade::has($moduleName)) {
            Log::warning("Module '{$moduleName}' not found.");
            return null;
        }

        if (!$enable && !$this->canDisable($moduleName)) {
            Log::warning("Module '{$moduleName}' cannot be disabled.");
            return null;
        }

        $enable ? ModuleFacade::enable($moduleName) : ModuleFacade::disable($moduleName);

        Artisan::call('optimize:clear');

        return Module::findData($moduleName);
    }

    /**
     * Install one or more modules from a ZIP file.
     *
     * @param string $relativeZipPath Relative or absolute path to the ZIP file.
     * @param bool $isAbsolute Set true if the path is absolute.
     * @return ModuleInstallResultData
     */
    public function installModulesFromZip(string $relativeZipPath, bool $isAbsolute = false): ModuleInstallResultData
    {
        $fullPath = $isAbsolute ? $relativeZipPath : storage_path("app/public/{$relativeZipPath}");
        $modulesPath = base_path('Modules');

        if (!File::exists($fullPath)) {
            Log::error("ZIP file not found: {$fullPath}");
            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $zip = new ZipArchive();
        if ($zip->open($fullPath) !== true) {
            Log::error("Cannot open ZIP file: {$fullPath}");
            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $tempExtractPath = storage_path('app/temp_module_extract');
        File::ensureDirectoryExists($tempExtractPath);
        File::cleanDirectory($tempExtractPath);

        if (!$this->extractZipSafely($zip, $tempExtractPath)) {
            $zip->close();
            File::delete($fullPath);
            Log::error("ZIP extraction failed: {$fullPath}");
            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        $zip->close();

        $entries = collect(File::directories($tempExtractPath))
            ->map(fn($path) => basename($path))
            ->values();

        $files = collect(File::files($tempExtractPath))
            ->map(fn($file) => $file->getFilename());

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
                        if (!empty($moduleConfig['name'])) {
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
                $moduleJsonPath = base_path("Modules/{$moduleName}/module.json");
                $moduleNameFromJson = $moduleName;

                if (File::exists($moduleJsonPath)) {
                    try {
                        $moduleConfig = json_decode(File::get($moduleJsonPath), true, 512, JSON_THROW_ON_ERROR);
                        if (!empty($moduleConfig['name'])) {
                            $moduleNameFromJson = $moduleConfig['name'];
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Invalid module.json for {$moduleName}: {$e->getMessage()}");
                    }
                }

                $installedModules[] = Module::findData($moduleName) ?? new \Alizharb\FilamentModuleManagerData\ModuleData(
                    name: $moduleNameFromJson,
                    alias: Str::lower($moduleNameFromJson),
                    description: null,
                    active: false,
                    path: base_path("Modules/{$moduleName}"),
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
     * @param string $repo User/repo or full URL.
     * @param string $branch Branch name, defaults to 'main'.
     * @return ModuleInstallResultData
     */
    public function installModuleFromGitHub(string $repo, string $branch = 'main'): ModuleInstallResultData
    {
        $tempPath = storage_path('app/temp_github_module');
        File::ensureDirectoryExists($tempPath);
        File::cleanDirectory($tempPath);

        if (!str_contains($repo, 'github.com')) {
            $repo = "https://github.com/{$repo}";
        }

        $branchesToTry = [$branch, 'master'];
        $zipDownloaded = false;
        $zipPath = "{$tempPath}/repo.zip";

        foreach ($branchesToTry as $b) {
            $zipUrl = rtrim($repo, '/') . "/archive/refs/heads/{$b}.zip";
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

        if (!$zipDownloaded) {
            Log::error("Failed to download any branch for GitHub repo: {$repo}");
            return new ModuleInstallResultData(installed: [], skipped: []);
        }

        return $this->installModulesFromZip($zipPath, true);
    }

    /**
     * Install module from local path.
     *
     * @param string $path Absolute or relative path.
     * @return ModuleInstallResultData
     */
    public function installModuleFromPath(string $path): ModuleInstallResultData
    {
        $modulesPath = base_path('Modules');
        $moduleName = basename($path);
        $destination = "{$modulesPath}/{$moduleName}";

        if (File::exists($destination)) {
            return new ModuleInstallResultData(installed: [], skipped: [$moduleName]);
        }

        if (File::isDirectory($path)) {
            File::copyDirectory($path, $destination);
        } else {
            File::ensureDirectoryExists($destination);
            File::copy($path, "{$destination}/" . basename($path));
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
     * Uninstall a module.
     *
     * @param string $moduleName
     * @return bool
     */
    public function uninstallModule(string $moduleName): bool
    {
        if (!ModuleFacade::has($moduleName) || !$this->canUninstall($moduleName)) {
            Log::warning("Cannot uninstall module: {$moduleName}");
            return false;
        }

        $path = ModuleFacade::find($moduleName)?->getPath();
        if (!$path || !File::isDirectory($path)) {
            Log::warning("Module path not found: {$moduleName}");
            return false;
        }

        DB::beginTransaction();
        try {
            File::deleteDirectory($path);
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            DB::commit();
            Log::info("Module uninstalled: {$moduleName}");
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Failed to uninstall module '{$moduleName}': {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Determine if a module can be disabled.
     *
     * @param string $moduleName
     * @return bool
     */
    public function canDisable(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);
        return $config['canBeDisabled'] ?? true;
    }

    /**
     * Determine if a module can be uninstalled.
     *
     * @param string $moduleName
     * @return bool
     */
    public function canUninstall(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);
        return $config['canBeUninstalled'] ?? true;
    }

    /**
     * Get module.json configuration.
     *
     * @param string $moduleName
     * @return array
     */
    protected function getModuleConfig(string $moduleName): array
    {
        $path = base_path("Modules/{$moduleName}/module.json");
        if (!File::exists($path)) {
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
     *
     * @param string $folder
     * @return bool
     */
    protected function isValidModule(string $folder): bool
    {
        $base = base_path("Modules/{$folder}");

        if (!File::isDirectory($base)) {
            return false;
        }

        if (File::exists("{$base}/module.json") || File::exists("{$base}/composer.json")) {
            return true;
        }

        $phpFiles = collect(File::allFiles($base))
            ->filter(fn($file) => $file->getExtension() === 'php');

        return $phpFiles->isNotEmpty();
    }

    /**
     * Check if a module exists.
     *
     * @param string $moduleName
     * @return bool
     */
    protected function moduleExists(string $moduleName): bool
    {
        return ModuleFacade::has($moduleName) || File::isDirectory(base_path("Modules/{$moduleName}"));
    }

    /**
     * Safely extract a ZIP archive.
     *
     * @param ZipArchive $zip
     * @param string $extractTo
     * @return bool
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
