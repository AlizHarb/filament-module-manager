<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Udvidelser',
        'label' => 'Modul Manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modul Navn',
        'version' => 'Version',
        'status' => 'Status',
        'module_path' => 'Modul Sti',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Vis',
        'enable' => 'Aktiver',
        'disable' => 'Deaktiver',
        'install' => 'Installer',
        'uninstall' => 'Afinstaller',
        'refresh' => 'Opdater',
        'view_module' => 'Vis Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modulet ":name" er blevet aktiveret.',
        'module_disabled' => 'Modulet ":name" er blevet deaktiveret.',
        'module_not_found' => 'Modul ikke fundet.',
        'modules_installed' => 'Moduler Installeret',
        'modules_installed_body' => 'Følgende moduler er blevet installeret: :names',
        'modules_skipped' => 'Moduler Sprunget Over',
        'modules_skipped_body' => 'Følgende moduler er blevet sprunget over: :names',
        'module_install_error' => 'Der opstod en fejl under modulinstallation.',
        'module_uninstalled' => 'Modul Afinstalleret',
        'module_uninstalled_body' => 'Modulet ":name" er blevet afinstalleret.',
        'module_uninstall_error' => 'Der opstod en fejl under afinstallation af modulet.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Modul Navn',
        'name_placeholder' => 'Søg efter modulnavn...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Aktiveret',
        'disabled' => 'Deaktiveret',
        'cannot_be_disabled' => 'Dette modul kan ikke deaktiveres.',
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
        'available' => 'Tilgængelige Moduler',
        'available_description' => 'Samlet antal installerede moduler',
        'active' => 'Aktive Moduler',
        'active_description' => 'I øjeblikket aktiveret',
        'disabled' => 'Deaktiverede Moduler',
        'disabled_description' => 'I øjeblikket deaktiveret',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modulnavn er påkrævet',
        'alias_required' => 'Modulalias er påkrævet',
        'alias_alpha_dash' => 'Modulalias må kun indeholde bogstaver, tal, bindestreger og understregninger',
        'path_required' => 'Modulsti er påkrævet',
        'version_regex' => 'Version skal følge semantisk versioneringsformat (f.eks. 1.0.0)',
    ],

];