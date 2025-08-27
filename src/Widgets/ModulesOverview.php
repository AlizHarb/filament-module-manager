<?php

namespace Alizharb\FilamentModuleManager\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Alizharb\FilamentModuleManager\Models\Module;

class ModulesOverview extends StatsOverviewWidget
{
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
