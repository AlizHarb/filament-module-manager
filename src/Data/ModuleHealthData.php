<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Data;

/**
 * Data Transfer Object for Module Health Check Results
 *
 * This DTO encapsulates the health status and diagnostic information
 * for a module, including individual check results and an overall score.
 */
final class ModuleHealthData extends Data
{
    /**
     * Create a new module health data instance.
     *
     * @param  string  $moduleName  The name of the module being checked
     * @param  string  $status  The overall health status: 'healthy', 'warning', or 'critical'
     * @param  array<string, bool>  $checks  Associative array of check names and their pass/fail status
     * @param  string|null  $message  Human-readable summary message of the health check results
     * @param  int  $score  Health score from 0-100, where 100 is perfect health
     * @param  string  $checkedAt  ISO 8601 timestamp of when the health check was performed
     */
    public function __construct(
        public string $moduleName,
        public string $status, // healthy, warning, critical
        public array $checks,
        public ?string $message,
        public int $score, // 0-100
        public string $checkedAt,
    ) {}

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
}
