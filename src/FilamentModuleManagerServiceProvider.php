<?php

namespace Alizharb\FilamentModuleManager;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Alizharb\FilamentModuleManager\Widgets\ModulesOverview;

class FilamentModuleManagerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-module-manager';

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-module-manager')
            ->hasTranslations()
            ->hasConfigFile()
            ->hasViews();
    }
}