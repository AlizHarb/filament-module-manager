<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class ModuleBackup extends Model
{
    use Sushi;

    protected $schema = [
        'id' => 'integer',
        'module_name' => 'string',
        'version' => 'string',
        'backup_path' => 'string',
        'size_bytes' => 'integer',
        'reason' => 'string',
        'created_by' => 'integer',
        'created_at' => 'string',
        'restored_at' => 'string',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Get rows from JSON storage
     */
    public function getRows(): array
    {
        $storagePath = storage_path('app/module-backups/backups.json');

        if (! File::exists($storagePath)) {
            return [];
        }

        try {
            return json_decode(File::get($storagePath), true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Save backup to JSON storage
     */
    public static function store(array $data): void
    {
        $storagePath = storage_path('app/module-backups/backups.json');
        File::ensureDirectoryExists(dirname($storagePath));

        $backups = [];
        if (File::exists($storagePath)) {
            $backups = json_decode(File::get($storagePath), true, 512, JSON_THROW_ON_ERROR) ?? [];
        }

        $data['id'] = count($backups) + 1;
        $data['created_at'] = now()->toIso8601String();
        $backups[] = $data;

        File::put($storagePath, json_encode($backups, JSON_PRETTY_PRINT));
    }

    /**
     * Update backup in JSON storage
     */
    public static function updateBackup(int $id, array $data): void
    {
        $storagePath = storage_path('app/module-backups/backups.json');

        if (! File::exists($storagePath)) {
            return;
        }

        $backups = json_decode(File::get($storagePath), true, 512, JSON_THROW_ON_ERROR) ?? [];

        foreach ($backups as &$backup) {
            if ($backup['id'] === $id) {
                $backup = array_merge($backup, $data);
                break;
            }
        }

        File::put($storagePath, json_encode($backups, JSON_PRETTY_PRINT));
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    protected function sushiShouldCache(): bool
    {
        return false; // Always fresh data
    }
}
