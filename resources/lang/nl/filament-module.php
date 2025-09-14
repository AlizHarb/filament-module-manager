<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensies',
        'label' => 'Modulebeheer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modulenaam',
        'version' => 'Versie',
        'status' => 'Status',
        'module_path' => 'Modulepad',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Bekijken',
        'enable' => 'Inschakelen',
        'disable' => 'Uitschakelen',
        'install' => 'Installeren',
        'uninstall' => 'Verwijderen',
        'refresh' => 'Vernieuwen',
        'view_module' => 'Bekijk Module: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Module ":name" is succesvol ingeschakeld.',
        'module_disabled' => 'Module ":name" is succesvol uitgeschakeld.',
        'module_not_found' => 'Module niet gevonden.',
        'modules_installed' => 'Modules Geïnstalleerd',
        'modules_installed_body' => 'De volgende modules zijn succesvol geïnstalleerd: :names',
        'modules_skipped' => 'Modules Overgeslagen',
        'modules_skipped_body' => 'De volgende modules zijn overgeslagen: :names',
        'module_install_error' => 'Er is een fout opgetreden tijdens de module-installatie.',
        'module_uninstalled' => 'Module verwijderd',
        'module_uninstalled_body' => 'Module ":name" is succesvol verwijderd.',
        'module_uninstall_error' => 'Er is een fout opgetreden bij het verwijderen van de module.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Modulenaam',
        'name_placeholder' => 'Zoeken op modulenaam...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Ingeschakeld',
        'disabled' => 'Uitgeschakeld',
        'cannot_be_disabled' => 'Deze module kan niet worden uitgeschakeld.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Module ZIP-bestand',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Beschikbare Modules',
        'available_description' => 'Totaal geïnstalleerde modules',
        'active' => 'Actieve Modules',
        'active_description' => 'Momenteel ingeschakeld',
        'disabled' => 'Uitgeschakelde Modules',
        'disabled_description' => 'Momenteel uitgeschakeld',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modulenaam is verplicht',
        'alias_required' => 'Module-alias is verplicht',
        'alias_alpha_dash' => 'Module-alias mag alleen letters, cijfers, streepjes en underscores bevatten',
        'path_required' => 'Modulepad is verplicht',
        'version_regex' => 'Versie moet het semantische versieformaat volgen (bijv. 1.0.0)',
    ],

];
