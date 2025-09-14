<?php

namespace Alizharb\FilamentModuleManager\Widgets;

use Alizharb\FilamentModuleManager\Models\Module;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Route;

class ModulesOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        if (! config('filament-module-manager.widgets.enabled', true)) {
            return false;
        }

        if (Route::currentRouteName() === 'filament.admin.pages.dashboard'
            && ! config('filament-module-manager.widgets.dashboard', false)) {
            return false;
        }

        return true;
    }

    protected function getStats(): array
    {
        $total = Module::count();
        $active = Module::where('active', true)->count();
        $disabled = Module::where('active', false)->count();

        return [
            Stat::make(__('filament-module-manager::filament-module.overview.available'), $total)
                ->description(__('filament-module-manager::filament-module.overview.available_description'))
                ->color('primary'),

            Stat::make(__('filament-module-manager::filament-module.overview.active'), $active)
                ->description(__('filament-module-manager::filament-module.overview.active_description'))
                ->color('success'),

            Stat::make(__('filament-module-manager::filament-module.overview.disabled'), $disabled)
                ->description(__('filament-module-manager::filament-module.overview.disabled_description'))
                ->color('danger'),
        ];
    }
}
