<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Exceptions;

use Exception;

/**
 * Exception thrown when module backup or restore operations fail
 *
 * This exception handles errors during backup creation, restoration,
 * and backup file management operations.
 */
class BackupException extends Exception
{
    /**
     * Create exception for failed backup
     *
     * @param  string  $moduleName  The module that failed to backup
     * @param  string  $reason  The reason for the failure
     */
    public static function backupFailed(string $moduleName, string $reason): static
    {
        return new static("Failed to backup module '{$moduleName}': {$reason}");
    }

    /**
     * Create exception for failed restore
     *
     * @param  int  $backupId  The ID of the backup that failed to restore
     * @param  string  $reason  The reason for the failure
     */
    public static function restoreFailed(int $backupId, string $reason): static
    {
        return new static("Failed to restore backup #{$backupId}: {$reason}");
    }

    /**
     * Create exception when backup is not found
     *
     * @param  int  $backupId  The ID of the missing backup
     */
    public static function backupNotFound(int $backupId): static
    {
        return new static("Backup #{$backupId} not found.");
    }
}
