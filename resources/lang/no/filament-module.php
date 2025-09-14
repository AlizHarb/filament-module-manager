<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Utvidelser',
        'label' => 'Modulbehandler',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modulnavn',
        'version' => 'Versjon',
        'status' => 'Status',
        'module_path' => 'Modulsti',
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
        'uninstall' => 'Avinstaller',
        'refresh' => 'Oppdater',
        'view_module' => 'Vis Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modulen ":name" er aktivert.',
        'module_disabled' => 'Modulen ":name" er deaktivert.',
        'module_not_found' => 'Modul ikke funnet.',
        'modules_installed' => 'Moduler Installert',
        'modules_installed_body' => 'Følgende moduler er installert: :names',
        'modules_skipped' => 'Moduler Hoppet Over',
        'modules_skipped_body' => 'Følgende moduler er hoppet over: :names',
        'module_install_error' => 'Det oppstod en feil under modulinstallasjon.',
        'module_uninstalled' => 'Modul Avinstallert',
        'module_uninstalled_body' => 'Modulen ":name" er avinstallert.',
        'module_uninstall_error' => 'Det oppstod en feil under avinstallasjon av modulen.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Modulnavn',
        'name_placeholder' => 'Søk etter modulnavn...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Aktivert',
        'disabled' => 'Deaktivert',
        'cannot_be_disabled' => 'Denne modulen kan ikke deaktiveres.',
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
        'available' => 'Tilgjengelige Moduler',
        'available_description' => 'Totalt antall installer moduler',
        'active' => 'Aktive Moduler',
        'active_description' => 'For øyeblikket aktivert',
        'disabled' => 'Deaktiverte Moduler',
        'disabled_description' => 'For øyeblikket deaktivert',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modulnavn er påkrevd',
        'alias_required' => 'Modulalias er påkrevd',
        'alias_alpha_dash' => 'Modulalias kan bare inneholde bokstaver, tall, bindestreker og understreker',
        'path_required' => 'Modulsti er påkrevd',
        'version_regex' => 'Versjon må følge semantisk versjonsformat (f.eks. 1.0.0)',
    ],

];
