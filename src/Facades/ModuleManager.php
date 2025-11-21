<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Facades;

use Alizharb\FilamentModuleManager\Services\ModuleManagerService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Alizharb\FilamentModuleManager\Data\ModuleData|null enable(string $moduleName)
 * @method static \Alizharb\FilamentModuleManager\Data\ModuleData|null disable(string $moduleName)
 * @method static \Alizharb\FilamentModuleManager\Data\ModuleInstallResultData installModulesFromZip(string $relativeZipPath, bool $isAbsolute = false)
 * @method static \Alizharb\FilamentModuleManager\Data\ModuleInstallResultData installModuleFromGitHub(string $repo, string $branch = 'main')
 * @method static \Alizharb\FilamentModuleManager\Data\ModuleInstallResultData installModuleFromPath(string $path)
 * @method static bool uninstallModule(string $moduleName)
 * @method static bool canDisable(string $moduleName)
 * @method static bool canUninstall(string $moduleName)
 * @method static array|null getModuleConfig(string $moduleName)
 * @method static bool isValidModule(string $folder)
 * @method static bool moduleExists(string $moduleName)
 *
 * @see ModuleManagerService
 */
class ModuleManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ModuleManagerService::class;
    }
}
