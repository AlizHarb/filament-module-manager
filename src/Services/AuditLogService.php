<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Models\ModuleAuditLog;
use Illuminate\Support\Facades\Request;

/**
 * Service for audit logging of module operations
 *
 * Tracks all module operations including user information, IP addresses,
 * timestamps, and success/failure status for compliance and debugging.
 */
class AuditLogService
{
    /**
     * Log a module action
     *
     * Records an operation with full context including user, IP, and result.
     *
     * @param  string  $action  The action being performed (e.g., 'install', 'uninstall', 'enable')
     * @param  string  $moduleName  The name of the module
     * @param  bool  $success  Whether the action succeeded
     * @param  array<string, mixed>|null  $metadata  Optional additional context data
     * @param  string|null  $errorMessage  Error message if action failed
     */
    public function log(
        string $action,
        string $moduleName,
        bool $success = true,
        ?array $metadata = null,
        ?string $errorMessage = null
    ): void {
        ModuleAuditLog::logAction([
            'module_name' => $moduleName,
            'action' => $action,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name ?? 'System',
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'success' => $success,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get logs for a specific module
     */
    public function getModuleLogs(string $moduleName, int $limit = 50): array
    {
        return ModuleAuditLog::where('module_name', $moduleName)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get logs for a specific user
     */
    public function getUserLogs(int $userId, int $limit = 50): array
    {
        return ModuleAuditLog::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get recent logs
     */
    public function getRecentLogs(int $limit = 100): array
    {
        return ModuleAuditLog::orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
