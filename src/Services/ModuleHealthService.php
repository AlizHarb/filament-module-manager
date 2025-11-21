<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleHealthData;
use Alizharb\FilamentModuleManager\Models\ModuleHealthCheck;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as ModuleFacade;

/**
 * Service for module health monitoring
 *
 * Performs various health checks on modules including file integrity,
 * dependency validation, and configuration checks. Provides health scores
 * and status categorization (healthy, warning, critical).
 */
class ModuleHealthService
{
    /**
     * Dependency service for checking module dependencies
     *
     * @var ModuleDependencyService
     */
    public function __construct(
        private ModuleDependencyService $dependencyService
    ) {}

    /**
     * Perform health check on a module
     *
     * Runs multiple checks and calculates an overall health score.
     *
     * @param  string  $moduleName  The module to check
     * @return ModuleHealthData Health check results with score and status
     */
    public function checkHealth(string $moduleName): ModuleHealthData
    {
        $module = ModuleFacade::find($moduleName);

        if (! $module) {
            return new ModuleHealthData(
                moduleName: $moduleName,
                status: 'critical',
                checks: ['exists' => false],
                message: 'Module not found',
                score: 0,
                checkedAt: now()->toIso8601String()
            );
        }

        $checks = [
            'exists' => true,
            'has_module_json' => $this->hasModuleJson($module->getPath()),
            'has_composer_json' => $this->hasComposerJson($module->getPath()),
            'has_service_provider' => $this->hasServiceProvider($module->getPath(), $moduleName),
            'dependencies_met' => $this->checkDependencies($moduleName),
            'files_intact' => $this->checkFilesIntact($module->getPath()),
        ];

        $score = (int) ((array_sum(array_map(fn ($v) => $v ? 1 : 0, $checks)) / count($checks)) * 100);

        $status = match (true) {
            $score >= 80 => 'healthy',
            $score >= 50 => 'warning',
            default => 'critical',
        };

        $message = $this->generateHealthMessage($checks, $score);

        $healthData = new ModuleHealthData(
            moduleName: $moduleName,
            status: $status,
            checks: $checks,
            message: $message,
            score: $score,
            checkedAt: now()->toIso8601String()
        );

        // Store result
        ModuleHealthCheck::store([
            'module_name' => $moduleName,
            'status' => $status,
            'checks' => $checks,
            'message' => $message,
            'score' => $score,
        ]);

        return $healthData;
    }

    /**
     * Check health of all modules
     */
    public function checkAllModules(): array
    {
        $results = [];

        foreach (ModuleFacade::all() as $module) {
            $results[] = $this->checkHealth($module->getName());
        }

        return $results;
    }

    /**
     * Get latest health check for a module
     */
    public function getLatestHealth(string $moduleName): ?ModuleHealthData
    {
        $check = ModuleHealthCheck::where('module_name', $moduleName)->first();

        if (! $check) {
            return null;
        }

        return new ModuleHealthData(
            moduleName: $check->module_name,
            status: $check->status,
            checks: is_string($check->checks) ? json_decode($check->checks, true) : $check->checks,
            message: $check->message,
            score: $check->score,
            checkedAt: $check->checked_at
        );
    }

    private function hasModuleJson(string $path): bool
    {
        return File::exists("{$path}/module.json");
    }

    private function hasComposerJson(string $path): bool
    {
        return File::exists("{$path}/composer.json");
    }

    private function hasServiceProvider(string $path, string $moduleName): bool
    {
        $providerPath = "{$path}/Providers/{$moduleName}ServiceProvider.php";

        return File::exists($providerPath);
    }

    private function checkDependencies(string $moduleName): bool
    {
        try {
            $dependencyService = app(ModuleDependencyService::class);
            $dependencyService->validateDependencies($moduleName);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function checkFilesIntact(string $path): bool
    {
        $requiredFiles = ['module.json'];

        foreach ($requiredFiles as $file) {
            if (! File::exists("{$path}/{$file}")) {
                return false;
            }
        }

        return true;
    }

    private function generateHealthMessage(array $checks, int $score): string
    {
        $failed = array_filter($checks, fn ($v) => ! $v);

        if (empty($failed)) {
            return 'All health checks passed';
        }

        $failedChecks = implode(', ', array_keys($failed));

        return "Failed checks: {$failedChecks} (Score: {$score}/100)";
    }
}
