<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Exceptions;

use Exception;

/**
 * Exception thrown when module update operations fail
 *
 * This exception handles errors during update checking and application,
 * including missing updates, failed downloads, and invalid versions.
 */
class UpdateException extends Exception
{
    /**
     * Create exception for failed update
     *
     * @param  string  $moduleName  The module that failed to update
     * @param  string  $reason  The reason for the failure
     */
    public static function updateFailed(string $moduleName, string $reason): static
    {
        return new static("Failed to update module '{$moduleName}': {$reason}");
    }

    /**
     * Create exception when no update is available
     *
     * @param  string  $moduleName  The module name
     */
    public static function noUpdateAvailable(string $moduleName): static
    {
        return new static("No update available for module '{$moduleName}'.");
    }

    /**
     * Create exception for an invalid version format.
     *
     * @param  string  $version  The invalid version string.
     */
    public static function invalidVersion(string $version): static
    {
        return new static("Invalid version format: '{$version}'");
    }
}
