<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class ModuleHealthCheck extends Model
{
    use Sushi;

    protected $schema = [
        'id' => 'integer',
        'module_name' => 'string',
        'status' => 'string',
        'checks' => 'text',
        'message' => 'text',
        'score' => 'integer',
        'checked_at' => 'string',
    ];

    protected $casts = [
        'checks' => 'array',
        'score' => 'integer',
    ];

    /**
     * Get rows from JSON storage
     */
    public function getRows(): array
    {
        $storagePath = storage_path('app/module-manager/health-checks.json');

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
     * Store health check result
     */
    public static function store(array $data): void
    {
        $storagePath = storage_path('app/module-manager/health-checks.json');
        File::ensureDirectoryExists(dirname($storagePath));

        $checks = [];
        if (File::exists($storagePath)) {
            $checks = json_decode(File::get($storagePath), true, 512, JSON_THROW_ON_ERROR) ?? [];
        }

        // Remove old check for same module
        $checks = array_filter($checks, fn ($check) => $check['module_name'] !== $data['module_name']);

        $data['id'] = count($checks) + 1;
        $data['checked_at'] = now()->toIso8601String();
        $data['checks'] = json_encode($data['checks'] ?? []);

        $checks[] = $data;

        File::put($storagePath, json_encode(array_values($checks), JSON_PRETTY_PRINT));
    }

    public function isHealthy(): bool
    {
        return $this->status === 'healthy';
    }

    public function hasWarnings(): bool
    {
        return $this->status === 'warning';
    }

    public function isCritical(): bool
    {
        return $this->status === 'critical';
    }

    protected function sushiShouldCache(): bool
    {
        return false; // Always fresh data
    }
}
