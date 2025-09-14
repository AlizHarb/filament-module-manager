<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Estensioni',
        'label' => 'Gestore Moduli',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nome Modulo',
        'version' => 'Versione',
        'status' => 'Stato',
        'module_path' => 'Percorso Modulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Visualizza',
        'enable' => 'Abilita',
        'disable' => 'Disabilita',
        'install' => 'Installa',
        'uninstall' => 'Disinstalla',
        'refresh' => 'Aggiorna',
        'view_module' => 'Visualizza Modulo: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Il modulo ":name" è stato abilitato con successo.',
        'module_disabled' => 'Il modulo ":name" è stato disabilitato con successo.',
        'module_not_found' => 'Modulo non trovato.',
        'modules_installed' => 'Moduli Installati',
        'modules_installed_body' => 'I seguenti moduli sono stati installati con successo: :names',
        'modules_skipped' => 'Moduli Saltati',
        'modules_skipped_body' => 'I seguenti moduli sono stati saltati: :names',
        'module_install_error' => 'Si è verificato un errore durante l\'installazione del modulo.',
        'module_uninstalled' => 'Modulo disinstallato',
        'module_uninstalled_body' => 'Il modulo ":name" è stato disinstallato con successo.',
        'module_uninstall_error' => 'Si è verificato un errore durante la disinstallazione del modulo.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Stato',
        'name' => 'Nome Modulo',
        'name_placeholder' => 'Cerca per nome modulo...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Abilitato',
        'disabled' => 'Disabilitado',
        'cannot_be_disabled' => 'Questo modulo non può essere disabilitato.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'File ZIP del Modulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Moduli Disponibili',
        'available_description' => 'Totale moduli installati',
        'active' => 'Moduli Attivi',
        'active_description' => 'Attualmente abilitati',
        'disabled' => 'Moduli Disabilitati',
        'disabled_description' => 'Attualmente disabilitati',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Il nome del modulo è obbligatorio',
        'alias_required' => 'L\'alias del modulo è obbligatorio',
        'alias_alpha_dash' => 'L\'alias del modulo può contenere solo lettere, numeri, trattini e trattini bassi',
        'path_required' => 'Il percorso del modulo è obbligatorio',
        'version_regex' => 'La versione deve seguire il formato di versionamento semantico (esempio: 1.0.0)',
    ],

];
