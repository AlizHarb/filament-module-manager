<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Policies;

use Alizharb\FilamentModuleManager\Models\Module;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class ModulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.view', 'view-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function view(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.view', 'view-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function create(User $user): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.install', 'install-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function update(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.update', 'update-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function delete(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.uninstall', 'uninstall-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function enable(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.enable', 'enable-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function disable(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        $permission = config('filament-module-manager.permissions.actions.disable', 'disable-modules');

        return $permission ? $user->can($permission) : true;
    }

    public function manageMaintenance(User $user, Module $module): bool
    {
        if (! config('filament-module-manager.permissions.enabled', false)) {
            return true;
        }

        // Use 'update' permission or a dedicated one for maintenance
        $permission = config('filament-module-manager.permissions.actions.update', 'update-modules');

        return $permission ? $user->can($permission) : true;
    }
}
