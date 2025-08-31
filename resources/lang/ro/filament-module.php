<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensii',
        'label' => 'Manager Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nume Modul',
        'version' => 'Versiune',
        'status' => 'Stare',
        'module_path' => 'Cale Modul',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Vizualizează',
        'enable' => 'Activează',
        'disable' => 'Dezactivează',
        'install' => 'Instalează',
        'uninstall' => 'Dezinstalează',
        'refresh' => 'Reîmprospătează',
        'view_module' => 'Vizualizează Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modulul ":name" a fost activat cu succes.',
        'module_disabled' => 'Modulul ":name" a fost dezactivat cu succes.',
        'module_not_found' => 'Modulul nu a fost găsit.',
        'modules_installed' => 'Module Instalate',
        'modules_installed_body' => 'Următoarele module au fost instalate cu succes: :names',
        'modules_skipped' => 'Module Oprite',
        'modules_skipped_body' => 'Următoarele module au fost oprite: :names',
        'module_install_error' => 'A apărut o eroare în timpul instalării modulului.',
        'module_uninstalled' => 'Modul Dezinstalat',
        'module_uninstalled_body' => 'Modulul ":name" a fost dezinstalat cu succes.',
        'module_uninstall_error' => 'A apărut o eroare la dezinstalarea modulului.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Stare',
        'name' => 'Nume Modul',
        'name_placeholder' => 'Caută după numele modulului...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Activat',
        'disabled' => 'Dezactivat',
        'cannot_be_disabled' => 'Acest modul nu poate fi dezactivat.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Fișier ZIP Modul',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Module Disponibile',
        'available_description' => 'Total module instalate',
        'active' => 'Module Active',
        'active_description' => 'În prezent activate',
        'disabled' => 'Module Dezactivate',
        'disabled_description' => 'În prezent dezactivate',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Numele modulului este obligatoriu',
        'alias_required' => 'Alias-ul modulului este obligatoriu',
        'alias_alpha_dash' => 'Alias-ul modulului poate conține doar litere, cifre, liniuțe și underscore-uri',
        'path_required' => 'Calea modulului este obligatorie',
        'version_regex' => 'Versiunea trebuie să urmeze formatul de versionare semantică (ex: 1.0.0)',
    ],

];