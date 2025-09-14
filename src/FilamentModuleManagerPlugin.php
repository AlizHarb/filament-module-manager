<?php

namespace Alizharb\FilamentModuleManager;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentModuleManagerPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-module-manager';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                \Alizharb\FilamentModuleManager\Pages\ModuleManager::class,
            ]);

        if (config('filament-module-manager.widgets.enabled', true)) {
            $panel->widgets(
                config('filament-module-manager.widgets.widgets', [])
            );
        }
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
