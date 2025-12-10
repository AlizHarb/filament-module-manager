<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleBackupData;
use Alizharb\FilamentModuleManager\Exceptions\BackupException;
use Alizharb\FilamentModuleManager\Models\ModuleBackup;
use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * Service for managing module backups and restoration
 *
 * Handles creation of ZIP backups, restoration from backups,
 * and backup metadata management using JSON storage.
 */
class ModuleBackupService
{
    /**
     * Create a backup of a module
     *
     * Creates a ZIP archive of the entire module directory and stores
     * metadata in JSON format for tracking and restoration.
     *
     * @param  string  $moduleName  The name of the module to backup
     * @param  string|null  $reason  Optional reason for creating the backup
     * @return ModuleBackupData Metadata about the created backup
     *
     * @throws BackupException If backup creation fails
     */
    public function createBackup(string $moduleName, ?string $reason = null): ModuleBackupData
    {
        $module = \Nwidart\Modules\Facades\Module::find($moduleName);

        if (! $module) {
            throw BackupException::backupFailed($moduleName, 'Module not found');
        }

        $backupDir = storage_path('app/module-backups');
        File::ensureDirectoryExists($backupDir);

        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupFileName = "{$moduleName}_{$timestamp}.zip";
        $backupPath = "{$backupDir}/{$backupFileName}";

        try {
            $zip = new ZipArchive;
            if ($zip->open($backupPath, ZipArchive::CREATE) !== true) {
                throw new \Exception('Failed to create ZIP archive');
            }

            $modulePath = $module->getPath();
            $files = File::allFiles($modulePath);

            foreach ($files as $file) {
                $relativePath = str_replace($modulePath.'/', '', $file->getPathname());
                $zip->addFile($file->getPathname(), $relativePath);
            }

            $zip->close();

            $sizeBytes = File::size($backupPath);

            // Store only the filename, not the full path
            ModuleBackup::store([
                'module_name' => $moduleName,
                'version' => $module->get('version'),
                'backup_path' => $backupFileName, // Store filename only
                'size_bytes' => $sizeBytes,
                'reason' => $reason,
                'created_by' => auth()->id(),
            ]);

            // Refresh Sushi cache
            ModuleBackup::query()->first();

            $backup = ModuleBackup::where('module_name', $moduleName)
                ->orderBy('id', 'desc')
                ->first();

            return new ModuleBackupData(
                id: $backup->id,
                moduleName: $backup->module_name,
                version: $backup->version,
                backupPath: storage_path('app/module-backups/'.$backup->backup_path), // Return full path
                sizeBytes: $backup->size_bytes,
                reason: $backup->reason,
                createdBy: $backup->created_by,
                createdAt: $backup->created_at,
            );
        } catch (\Throwable $e) {
            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }

            throw BackupException::backupFailed($moduleName, $e->getMessage());
        }
    }

    /**
     * Resolve backup path - handles both old full paths and new filename-only format
     */
    private function resolveBackupPath(string $storedPath): string
    {
        // If it's already an absolute path, use it as-is (backward compatibility)
        if (str_starts_with($storedPath, '/')) {
            return $storedPath;
        }

        // Otherwise, resolve it as a filename
        return storage_path('app/module-backups/'.$storedPath);
    }

    /**
     * Restore a module from backup
     */
    public function restoreBackup(int $backupId): bool
    {
        $backup = ModuleBackup::find($backupId);

        if (! $backup) {
            throw BackupException::backupNotFound($backupId);
        }

        // Resolve the full path from the stored filename
        $backupPath = $this->resolveBackupPath($backup->backup_path);

        if (! File::exists($backupPath)) {
            throw BackupException::restoreFailed($backupId, "Backup file not found at: {$backupPath}");
        }

        try {
            $modulesPath = base_path('Modules');
            $modulePath = "{$modulesPath}/{$backup->module_name}";

            // Remove existing module
            if (File::exists($modulePath)) {
                File::deleteDirectory($modulePath);
            }

            // Extract backup
            $zip = new ZipArchive;
            if ($zip->open($backupPath) !== true) {
                throw new \Exception('Failed to open backup ZIP');
            }

            File::ensureDirectoryExists($modulePath);
            $zip->extractTo($modulePath);
            $zip->close();

            // Mark as restored
            ModuleBackup::updateBackup($backupId, [
                'restored_at' => now()->toIso8601String(),
            ]);

            // Rescan modules
            \Nwidart\Modules\Facades\Module::scan();

            return true;
        } catch (\Throwable $e) {
            throw BackupException::restoreFailed($backupId, $e->getMessage());
        }
    }

    /**
     * List all backups for a module
     */
    public function listBackups(string $moduleName): array
    {
        return ModuleBackup::where('module_name', $moduleName)
            ->get()
            ->map(fn ($backup) => new ModuleBackupData(
                id: $backup->id,
                moduleName: $backup->module_name,
                version: $backup->version,
                backupPath: $this->resolveBackupPath($backup->backup_path), // Resolve to full path
                sizeBytes: $backup->size_bytes,
                reason: $backup->reason,
                createdBy: $backup->created_by,
                createdAt: $backup->created_at,
                restoredAt: $backup->restored_at,
            ))
            ->toArray();
    }

    /**
     * Delete a backup
     */
    public function deleteBackup(int $backupId): bool
    {
        $backup = ModuleBackup::find($backupId);

        if (! $backup) {
            return false;
        }

        // Resolve the full path from the stored filename
        $backupPath = $this->resolveBackupPath($backup->backup_path);

        if (File::exists($backupPath)) {
            File::delete($backupPath);
        }

        // Remove from JSON storage
        $storagePath = storage_path('app/module-backups/backups.json');
        if (File::exists($storagePath)) {
            $backups = json_decode(File::get($storagePath), true) ?? [];
            $backups = array_filter($backups, fn ($b) => $b['id'] !== $backupId);
            File::put($storagePath, json_encode(array_values($backups), JSON_PRETTY_PRINT));
        }

        return true;
    }
}
