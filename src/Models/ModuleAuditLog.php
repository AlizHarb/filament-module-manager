<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class ModuleAuditLog extends Model
{
    use Sushi;

    protected $schema = [
        'id' => 'integer',
        'module_name' => 'string',
        'action' => 'string',
        'user_id' => 'integer',
        'user_name' => 'string',
        'metadata' => 'string',
        'ip_address' => 'string',
        'user_agent' => 'text',
        'success' => 'boolean',
        'error_message' => 'text',
        'created_at' => 'string',
    ];

    protected $casts = [
        'metadata' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Get rows from JSON storage
     */
    public function getRows(): array
    {
        $storagePath = storage_path('app/module-manager/audit-logs.json');

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
     * Log an action to JSON storage
     */
    public static function logAction(array $data): void
    {
        $storagePath = storage_path('app/module-manager/audit-logs.json');
        File::ensureDirectoryExists(dirname($storagePath));

        $logs = [];
        if (File::exists($storagePath)) {
            $logs = json_decode(File::get($storagePath), true, 512, JSON_THROW_ON_ERROR) ?? [];
        }

        $data['id'] = count($logs) + 1;
        $data['created_at'] = now()->toIso8601String();
        $data['metadata'] = isset($data['metadata']) ? json_encode($data['metadata']) : null;

        $logs[] = $data;

        // Keep only last 1000 logs
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }

        File::put($storagePath, json_encode($logs, JSON_PRETTY_PRINT));
    }

    protected function sushiShouldCache(): bool
    {
        return false; // Always fresh data
    }
}
