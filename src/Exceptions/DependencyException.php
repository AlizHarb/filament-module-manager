<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Exceptions;

use Exception;

/**
 * Exception thrown when module dependency requirements are not met
 *
 * This exception is used for various dependency-related errors including
 * missing dependencies, circular dependencies, and conflicts with dependents.
 */
class DependencyException extends Exception
{
    /**
     * Create exception for missing dependency
     *
     * @param  string  $moduleName  The module that has the unmet dependency
     * @param  string  $dependency  The missing dependency module name
     */
    public static function missingDependency(string $moduleName, string $dependency): static
    {
        return new static("Module '{$moduleName}' requires '{$dependency}' which is not installed.");
    }

    /**
     * Create exception for circular dependency
     *
     * @param  string  $moduleName  The module involved in the circular dependency
     * @param  array<string>  $chain  The dependency chain showing the circular reference
     */
    public static function circularDependency(string $moduleName, array $chain): static
    {
        $chainStr = implode(' -> ', $chain);

        return new static("Circular dependency detected for module '{$moduleName}': {$chainStr}");
    }

    /**
     * Create exception when module has active dependents
     *
     * @param  string  $moduleName  The module that cannot be disabled/uninstalled
     * @param  array<string>  $dependents  List of modules that depend on this module
     */
    public static function hasDependents(string $moduleName, array $dependents): static
    {
        $dependentsList = implode(', ', $dependents);

        return new static("Module '{$moduleName}' cannot be disabled/uninstalled. Required by: {$dependentsList}");
    }
}
