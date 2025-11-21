<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Data;

/**
 * Data Transfer Object for Module Dependency Information
 *
 * Represents a dependency relationship between two modules, including
 * version constraints and whether the dependency is required or optional.
 */
final class ModuleDependencyData extends Data
{
    /**
     * Create a new module dependency data instance.
     *
     * @param  string  $moduleName  The name of the module that has the dependency
     * @param  string  $dependsOn  The name of the module that is depended upon
     * @param  string|null  $versionConstraint  Optional version constraint (e.g., '^1.0', '~2.0', '*')
     * @param  bool  $required  Whether this dependency is required (true) or optional (false)
     */
    public function __construct(
        public string $moduleName,
        public string $dependsOn,
        public ?string $versionConstraint = null,
        public bool $required = true,
    ) {}
}
