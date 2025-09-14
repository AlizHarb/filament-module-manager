<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Erweiterungen',
        'label' => 'Modul-Manager',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modulname',
        'version' => 'Version',
        'status' => 'Status',
        'module_path' => 'Modulpfad',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Anzeigen',
        'enable' => 'Aktivieren',
        'disable' => 'Deaktivieren',
        'install' => 'Installieren',
        'uninstall' => 'Deinstallieren',
        'refresh' => 'Aktualisieren',
        'view_module' => 'Modul anzeigen: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modul ":name" wurde erfolgreich aktiviert.',
        'module_disabled' => 'Modul ":name" wurde erfolgreich deaktiviert.',
        'module_not_found' => 'Modul nicht gefunden.',
        'modules_installed' => 'Module installiert',
        'modules_installed_body' => 'Die folgenden Module wurden erfolgreich installiert: :names',
        'modules_skipped' => 'Module übersprungen',
        'modules_skipped_body' => 'Die folgenden Module wurden übersprungen: :names',
        'module_install_error' => 'Während der Modulinstallation ist ein Fehler aufgetreten.',
        'module_uninstalled' => 'Modul deinstalliert',
        'module_uninstalled_body' => 'Modul ":name" wurde erfolgreich deinstalliert.',
        'module_uninstall_error' => 'Beim Deinstallieren des Moduls ist ein Fehler aufgetreten.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Modulname',
        'name_placeholder' => 'Nach Modulname suchen...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Aktiviert',
        'disabled' => 'Deaktiviert',
        'cannot_be_disabled' => 'Dieses Modul kann nicht deaktiviert werden.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Modul-ZIP-Datei',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Verfügbare Module',
        'available_description' => 'Gesamtzahl installierter Module',
        'active' => 'Aktive Module',
        'active_description' => 'Aktuell aktiviert',
        'disabled' => 'Deaktivierte Module',
        'disabled_description' => 'Aktuell deaktiviert',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modulname ist erforderlich',
        'alias_required' => 'Modulalias ist erforderlich',
        'alias_alpha_dash' => 'Modulalias darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten',
        'path_required' => 'Modulpfad ist erforderlich',
        'version_regex' => 'Version muss dem semantischen Versionierungsformat folgen (z.B. 1.0.0)',
    ],

];
