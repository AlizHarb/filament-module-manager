<?php

/**
 * Filament Module Manager Configuration
 *
 * This configuration file is used by the Filament Module Manager plugin
 * for FilamentPHP 4. It handles navigation settings, module upload settings,
 * and other module manager-related configurations.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation Settings
    |--------------------------------------------------------------------------
    |
    | Configure how the module manager appears in the Filament admin panel navigation.
    | You can enable/disable it, set its icon, label, group, and sort order.
    |
    */
    'navigation' => [
        /**
         * Whether to register the module manager in the navigation menu.
         *
         * @var bool
         */
        'register' => true,

        /**
         * Navigation sort order.
         *
         * Lower numbers appear first.
         *
         * @var int
         */
        'sort' => 100,

        /**
         * Heroicon name for the navigation icon.
         *
         * @var string
         */
        'icon' => 'heroicon-o-code-bracket',

        /**
         * Translation key for the navigation group.
         *
         * @var string
         */
        'group' => 'filament-module-manager::filament-module.navigation.group',

        /**
         * Translation key for the navigation label.
         *
         * @var string
         */
        'label' => 'filament-module-manager::filament-module.navigation.label',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Upload Settings
    |--------------------------------------------------------------------------
    |
    | Configure how modules can be uploaded via the Filament admin panel.
    | This includes disk storage, temporary directory, and max file size.
    |
    */
    'upload' => [
        /**
         * The disk where uploaded modules are temporarily stored.
         *
         * @var string
         */
        'disk' => 'public',

        /**
         * Temporary directory path for uploaded modules.
         *
         * @var string
         */
        'temp_directory' => 'temp/modules',

        /**
         * Maximum upload size in bytes.
         *
         * @var int
         */
        'max_size' => 20 * 1024 * 1024, // 20 MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Settings
    |--------------------------------------------------------------------------
    |
    | Configure the module manager widgets and their display locations.
    | You can enable/disable widgets and control where they appear.
    |
    */
    'widgets' => [
        /**
         * Whether widgets are enabled globally.
         *
         * @var bool
         */
        'enabled' => true,

        /**
         * Whether to show widgets on the Filament dashboard page.
         *
         * @var bool
         */
        'dashboard' => true,

        /**
         * Whether to show widgets on the module manager page.
         *
         * @var bool
         */
        'page' => true,

        /**
         * Array of widget classes to register.
         *
         * @var array
         */
        'widgets' => [
            \Alizharb\FilamentModuleManager\Widgets\ModulesOverview::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions Settings
    |--------------------------------------------------------------------------
    |
    | Configure role-based access control for module operations.
    |
    */
    'permissions' => [
        /**
         * Enable permission checks for module operations.
         *
         * @var bool
         */
        'enabled' => false,

        /**
         * Permissions required for each action.
         *
         * @var array
         */
        'actions' => [
            'view' => 'view-modules',
            'install' => 'install-modules',
            'uninstall' => 'uninstall-modules',
            'enable' => 'enable-modules',
            'disable' => 'disable-modules',
            'update' => 'update-modules',
            'backup' => 'backup-modules',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Backup Settings
    |--------------------------------------------------------------------------
    |
    | Configure automatic backups before module operations.
    |
    */
    'backups' => [
        /**
         * Enable automatic backups.
         *
         * @var bool
         */
        'enabled' => true,

        /**
         * Automatically backup before updates.
         *
         * @var bool
         */
        'backup_before_update' => true,

        /**
         * Automatically backup before uninstall.
         *
         * @var bool
         */
        'backup_before_uninstall' => true,

        /**
         * Number of days to retain backups.
         *
         * @var int
         */
        'retention_days' => 30,

        /**
         * Storage path for backups (relative to storage/app/).
         *
         * @var string
         */
        'storage_path' => 'module-backups',

        /**
         * Maximum number of backups to keep per module.
         *
         * @var int
         */
        'max_backups_per_module' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Check Settings
    |--------------------------------------------------------------------------
    |
    | Configure module health monitoring.
    |
    */
    'health_checks' => [
        /**
         * Enable health checks.
         *
         * @var bool
         */
        'enabled' => true,

        /**
         * Automatically check health after install/update.
         *
         * @var bool
         */
        'auto_check' => true,

        /**
         * Schedule for automatic health checks (cron expression).
         *
         * @var string|null
         */
        'schedule' => null, // e.g., 'daily', '0 */6 * * *'

        /**
         * Storage path for health check data (relative to storage/app/).
         *
         * @var string
         */
        'storage_path' => 'module-manager/health-checks.json',

        /**
         * Cache duration for health check results (in seconds).
         *
         * @var int
         */
        'cache_duration' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Update Settings
    |--------------------------------------------------------------------------
    |
    | Configure module update checking.
    |
    */
    'updates' => [
        /**
         * Enable update checking.
         *
         * @var bool
         */
        'enabled' => true,

        /**
         * Automatically check for updates.
         *
         * @var bool
         */
        'auto_check' => false,

        /**
         * Frequency for checking updates (in hours).
         *
         * @var int
         */
        'check_frequency' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Log Settings
    |--------------------------------------------------------------------------
    |
    | Configure audit logging for module operations.
    |
    */
    'audit' => [
        /**
         * Enable audit logging.
         *
         * @var bool
         */
        'enabled' => true,

        /**
         * Storage path for audit logs (relative to storage/app/).
         *
         * @var string
         */
        'storage_path' => 'module-manager/audit-logs.json',

        /**
         * Maximum number of log entries to keep.
         *
         * @var int
         */
        'max_logs' => 1000,

        /**
         * Log retention period (in days).
         *
         * @var int
         */
        'retention_days' => 90,
    ],

    /*
    |--------------------------------------------------------------------------
    | GitHub Integration Settings
    |--------------------------------------------------------------------------
    |
    | Configure GitHub API integration for enhanced features.
    |
    */
    'github' => [
        /**
         * GitHub personal access token for API requests.
         * Increases rate limits and allows access to private repositories.
         *
         * @var string|null
         */
        'token' => env('GITHUB_TOKEN'),

        /**
         * Default branch to use when installing from GitHub.
         *
         * @var string
         */
        'default_branch' => 'main',

        /**
         * Fallback branch if default branch doesn't exist.
         *
         * @var string
         */
        'fallback_branch' => 'master',
    ],

];
