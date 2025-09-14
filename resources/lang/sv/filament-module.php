<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Tillägg',
        'label' => 'Modulhanterare',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modulnamn',
        'version' => 'Version',
        'status' => 'Status',
        'module_path' => 'Modulsökväg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Visa',
        'enable' => 'Aktivera',
        'disable' => 'Inaktivera',
        'install' => 'Installera',
        'uninstall' => 'Avinstallera',
        'refresh' => 'Uppdatera',
        'view_module' => 'Visa Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modulen ":name" har aktiverats.',
        'module_disabled' => 'Modulen ":name" har inaktiverats.',
        'module_not_found' => 'Modulen hittades inte.',
        'modules_installed' => 'Moduler Installerade',
        'modules_installed_body' => 'Följande moduler har installerats: :names',
        'modules_skipped' => 'Moduler Hoppades Över',
        'modules_skipped_body' => 'Följande moduler hoppades över: :names',
        'module_install_error' => 'Ett fel uppstod under modulinstallation.',
        'module_uninstalled' => 'Modul Avinstallerad',
        'module_uninstalled_body' => 'Modulen ":name" har avinstallerats.',
        'module_uninstall_error' => 'Ett fel uppstod under avinstallation av modulen.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Modulnamn',
        'name_placeholder' => 'Sök efter modulnamn...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Aktiverad',
        'disabled' => 'Inaktiverad',
        'cannot_be_disabled' => 'Denna modul kan inte inaktiveras.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Modul ZIP-fil',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Tillgängliga Moduler',
        'available_description' => 'Totalt installerade moduler',
        'active' => 'Aktiva Moduler',
        'active_description' => 'För närvarande aktiverade',
        'disabled' => 'Inaktiverade Moduler',
        'disabled_description' => 'För närvarande inaktiverade',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modulnamn krävs',
        'alias_required' => 'Modulalias krävs',
        'alias_alpha_dash' => 'Modulalias får endast innehålla bokstäver, siffror, bindestreck och understreck',
        'path_required' => 'Modulsökväg krävs',
        'version_regex' => 'Version måste följa semantisk versionshanteringsformat (t.ex. 1.0.0)',
    ],

];
