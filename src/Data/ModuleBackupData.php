<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Data;

/**
 * Data Transfer Object for Module Backup Metadata
 *
 * Represents metadata about a module backup, including file location,
 * size, creation details, and restoration status.
 */
final class ModuleBackupData extends Data
{
    /**
     * Create a new module backup data instance.
     *
     * @param  int  $id  Unique identifier for the backup
     * @param  string  $moduleName  The name of the backed-up module
     * @param  string  $version  The version of the module at backup time
     * @param  string  $backupPath  Absolute path to the backup ZIP file
     * @param  int  $sizeBytes  Size of the backup file in bytes
     * @param  string|null  $reason  Optional reason for creating the backup (e.g., 'Before update')
     * @param  int|null  $createdBy  User ID of who created the backup, null for system-created
     * @param  string|null  $createdAt  ISO 8601 timestamp of when the backup was created
     * @param  string|null  $restoredAt  ISO 8601 timestamp of when the backup was restored, null if not restored
     */
    public function __construct(
        public int $id,
        public string $moduleName,
        public ?string $version,
        public string $backupPath,
        public int $sizeBytes,
        public ?string $reason = null,
        public ?int $createdBy = null,
        public ?string $createdAt = null,
        public ?string $restoredAt = null,
    ) {}
}
