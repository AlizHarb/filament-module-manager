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

class ModuleManagerService
{
    /**
     * Enable or disable a module.
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
     */
    public function installModulesFromZip(string $relativeZipPath): ModuleInstallResultData
    {
        $fullPath = storage_path("app/public/{$relativeZipPath}");
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

        // Optional package.json for multi-module ZIP
        $packageJsonPath = "{$tempExtractPath}/package.json";
        $packageModules = [];
        if (File::exists($packageJsonPath)) {
            try {
                $packageData = json_decode(File::get($packageJsonPath), true, 512, JSON_THROW_ON_ERROR);
                $packageModules = $packageData['modules'] ?? [];
            } catch (Throwable $e) {
                Log::warning("Invalid package.json: {$e->getMessage()}");
            }
        }

        $entries = collect(File::directories($tempExtractPath))
            ->map(fn($path) => basename($path))
            ->values();
        $files = collect(File::files($tempExtractPath))->map(fn($file) => $file->getFilename());
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
                $destination = "{$modulesPath}/{$moduleName}";

                // Skip if already exists
                if (File::exists($destination)) {
                    $skippedModules[] = $moduleName;
                    continue;
                }

                // Move folder or files
                if (File::isDirectory($sourcePath)) {
                    File::moveDirectory($sourcePath, $destination);
                } else {
                    File::ensureDirectoryExists($destination);
                    File::move($sourcePath, "{$destination}/{$entry}");
                }

                // Skip if package.json exists and module not listed
                if (!empty($packageModules) && !in_array($moduleName, $packageModules, true)) {
                    File::deleteDirectory($destination);
                    $skippedModules[] = $moduleName;
                    continue;
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

        // Refresh Nwidart modules after all moves
        ModuleFacade::scan();

        $installedModules = [];

        // Collect ModuleData and enable recognized modules
        foreach ($movedModules as $moduleName) {
            if (ModuleFacade::has($moduleName)) {
                ModuleFacade::enable($moduleName);
                $installedModules[] = Module::findData($moduleName);
            } else {
                // Module valid but not recognized by Nwidart
                $installedModules[] = Module::findData($moduleName) ?? new \Alizharb\FilamentModuleManagerData\ModuleData(
                    name: $moduleName,
                    alias: Str::lower($moduleName),
                    description: null,
                    active: false,
                    path: base_path("Modules/{$moduleName}"),
                    version: null,
                );
            }
        }

        // Clean up
        File::deleteDirectory($tempExtractPath);
        File::delete($fullPath);

        // Clear caches
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');

        return new ModuleInstallResultData(
            installed: $installedModules,
            skipped: $skippedModules
        );
    }

    /**
     * Uninstall a module.
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
     * Check if a module can be disabled.
     */
    public function canDisable(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);
        return $config['canBeDisabled'] ?? true;
    }

    /**
     * Check if a module can be uninstalled.
     */
    public function canUninstall(string $moduleName): bool
    {
        $config = $this->getModuleConfig($moduleName);
        return $config['canBeUninstalled'] ?? true;
    }

    /**
     * Get module.json configuration.
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
     * Check if a folder is a valid module.
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
     */
    protected function moduleExists(string $moduleName): bool
    {
        return ModuleFacade::has($moduleName) || File::isDirectory(base_path("Modules/{$moduleName}"));
    }

    /**
     * Safely extract ZIP file.
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
