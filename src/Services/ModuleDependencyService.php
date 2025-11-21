<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleDependencyData;
use Alizharb\FilamentModuleManager\Exceptions\DependencyException;
use Alizharb\FilamentModuleManager\Models\ModuleDependency;
use Illuminate\Support\Collection;
use Nwidart\Modules\Facades\Module as ModuleFacade;

/**
 * Service for managing module dependencies
 *
 * Handles dependency validation, resolution, and relationship management
 * between modules including version constraints and circular dependency detection.
 */
class ModuleDependencyService
{
    /**
     * Validate that all dependencies for a module are met
     *
     * Checks if all required dependencies are installed and meet version constraints.
     *
     * @param  string  $moduleName  The module to validate dependencies for
     * @return bool True if all dependencies are met
     *
     * @throws DependencyException If any dependency is missing or version mismatch
     */
    public function validateDependencies(string $moduleName): bool
    {
        $dependencies = $this->getModuleDependencies($moduleName);

        foreach ($dependencies as $dependency) {
            if (! ModuleFacade::has($dependency->dependsOn)) {
                throw DependencyException::missingDependency($moduleName, $dependency->dependsOn);
            }

            if ($dependency->versionConstraint) {
                $installedVersion = ModuleFacade::find($dependency->dependsOn)?->get('version');
                if (! $this->versionMatches($installedVersion, $dependency->versionConstraint)) {
                    throw DependencyException::missingDependency(
                        $moduleName,
                        "{$dependency->dependsOn} {$dependency->versionConstraint}"
                    );
                }
            }
        }

        return true;
    }

    /**
     * Get all dependencies for a module
     *
     * @return Collection<ModuleDependencyData>
     */
    public function getModuleDependencies(string $moduleName): Collection
    {
        return ModuleDependency::where('module_name', $moduleName)
            ->get()
            ->map(fn ($dep) => new ModuleDependencyData(
                moduleName: $dep->module_name,
                dependsOn: $dep->depends_on,
                versionConstraint: $dep->version_constraint,
                required: $dep->required
            ));
    }

    /**
     * Get modules that depend on this module
     *
     * Returns a list of module names that have this module as a dependency.
     *
     * @param  string  $moduleName  The module to find dependents for
     * @return array<string> Array of module names that depend on this module
     */
    public function getDependents(string $moduleName): array
    {
        return ModuleDependency::where('depends_on', $moduleName)
            ->pluck('module_name')
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Check if a module can be disabled
     *
     * A module can only be disabled if no other enabled modules depend on it.
     *
     * @param  string  $moduleName  The module to check
     * @return bool True if the module can be safely disabled
     */
    public function canDisable(string $moduleName): bool
    {
        $dependents = $this->getDependents($moduleName);

        foreach ($dependents as $dependent) {
            if (ModuleFacade::find($dependent)?->isEnabled()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if a module can be uninstalled
     *
     * A module can only be uninstalled if no other modules depend on it.
     *
     * @param  string  $moduleName  The module to check
     * @return bool True if the module can be safely uninstalled
     *
     * @throws DependencyException If the module has dependents
     */
    public function canUninstall(string $moduleName): bool
    {
        $dependents = $this->getDependents($moduleName);

        if (! empty($dependents)) {
            throw DependencyException::hasDependents($moduleName, $dependents);
        }

        return true;
    }

    /**
     * Get full dependency tree for a module
     *
     * Recursively builds a tree of all dependencies and their sub-dependencies.
     *
     * @param  string  $moduleName  The module to get dependency tree for
     * @param  array<string>  $visited  Internal parameter for tracking visited modules
     * @return array<string, array> Nested array representing the dependency tree
     *
     * @throws DependencyException If a circular dependency is detected
     */
    public function getDependencyTree(string $moduleName, array $visited = []): array
    {
        if (in_array($moduleName, $visited)) {
            throw DependencyException::circularDependency($moduleName, $visited);
        }

        $visited[] = $moduleName;
        $tree = [$moduleName => []];

        $dependencies = $this->getModuleDependencies($moduleName);

        foreach ($dependencies as $dependency) {
            if (ModuleFacade::has($dependency->dependsOn)) {
                $tree[$moduleName][$dependency->dependsOn] = $this->getDependencyTree(
                    $dependency->dependsOn,
                    $visited
                );
            }
        }

        return $tree;
    }

    /**
     * Resolve dependencies in installation order (topological sort)
     *
     * Orders modules so that dependencies are installed before dependents.
     *
     * @param  array<string>  $moduleNames  List of module names to resolve
     * @return array<string> Modules sorted in dependency order
     *
     * @throws DependencyException If circular dependencies are detected
     */
    public function resolveDependencies(array $moduleNames): array
    {
        $resolved = [];
        $unresolved = [];

        foreach ($moduleNames as $moduleName) {
            $this->resolveDependency($moduleName, $resolved, $unresolved);
        }

        return $resolved;
    }

    /**
     * Recursive dependency resolution helper
     *
     * @param  string  $moduleName  Current module being resolved
     * @param  array<string>  $resolved  Reference to array of resolved modules
     * @param  array<string>  $unresolved  Reference to array of currently resolving modules
     *
     * @throws DependencyException If circular dependency detected
     */
    private function resolveDependency(string $moduleName, array &$resolved, array &$unresolved): void
    {
        $unresolved[] = $moduleName;

        $dependencies = $this->getModuleDependencies($moduleName);

        foreach ($dependencies as $dependency) {
            if (! in_array($dependency->dependsOn, $resolved)) {
                if (in_array($dependency->dependsOn, $unresolved)) {
                    throw DependencyException::circularDependency($moduleName, $unresolved);
                }

                $this->resolveDependency($dependency->dependsOn, $resolved, $unresolved);
            }
        }

        $resolved[] = $moduleName;
        $unresolved = array_diff($unresolved, [$moduleName]);
    }

    /**
     * Check if a version matches a constraint
     *
     * Supports ^ (caret), ~ (tilde), and exact version matching.
     *
     * @param  string|null  $version  The installed version
     * @param  string  $constraint  The version constraint (e.g., '^1.0', '~2.0', '1.5.0')
     * @return bool True if version matches the constraint
     */
    private function versionMatches(?string $version, string $constraint): bool
    {
        if (! $version) {
            return false;
        }

        // Simple version matching (can be enhanced with composer/semver)
        if (str_starts_with($constraint, '^')) {
            $minVersion = ltrim($constraint, '^');

            return version_compare($version, $minVersion, '>=');
        }

        if (str_starts_with($constraint, '~')) {
            $minVersion = ltrim($constraint, '~');

            return version_compare($version, $minVersion, '>=');
        }

        return version_compare($version, $constraint, '=');
    }
}
