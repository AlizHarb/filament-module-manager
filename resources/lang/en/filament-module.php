<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensions',
        'label' => 'Modules Manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Module Name',
        'version' => 'Version',
        'status' => 'Status',
        'module_path' => 'Module Path',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'View',
        'enable' => 'Enable',
        'disable' => 'Disable',
        'install' => 'Install',
        'uninstall' => 'Uninstall',
        'refresh' => 'Refresh',
        'view_module' => 'View Module: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Module ":name" has been enabled successfully.',
        'module_disabled' => 'Module ":name" has been disabled successfully.',
        'module_not_found' => 'Module not found.',
        'modules_installed' => 'Modules Installed',
        'modules_installed_body' => 'The following modules have been installed successfully: :names',
        'modules_skipped' => 'Modules Skipped',
        'modules_skipped_body' => 'The following modules were skipped: :names',
        'module_install_error' => 'An error occurred during module installation.',
        'module_uninstalled' => 'Module uninstalled',
        'module_uninstalled_body' => 'Module ":name" has been uninstalled successfully.',
        'module_uninstall_error' => 'An error occurred while uninstalling the module.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Module Name',
        'name_placeholder' => 'Search by module name...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Enabled',
        'disabled' => 'Disabled',
        'cannot_be_disabled' => 'This module cannot be disabled.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Module ZIP File',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Available Modules',
        'available_description' => 'Total installed modules',
        'active' => 'Active Modules',
        'active_description' => 'Currently enabled',
        'disabled' => 'Disabled Modules',
        'disabled_description' => 'Currently disabled',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Module name is required',
        'alias_required' => 'Module alias is required',
        'alias_alpha_dash' => 'Module alias can only contain letters, numbers, dashes and underscores',
        'path_required' => 'Module path is required',
        'version_regex' => 'Version must follow semantic versioning format (e.g., 1.0.0)',
    ],

];
