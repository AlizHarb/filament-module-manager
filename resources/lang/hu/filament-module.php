<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Kiegészítők',
        'label' => 'Modul Kezelő',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modul Név',
        'version' => 'Verzió',
        'status' => 'Állapot',
        'module_path' => 'Modul Útvonal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Megtekintés',
        'enable' => 'Bekapcsolás',
        'disable' => 'Kikapcsolás',
        'install' => 'Telepítés',
        'uninstall' => 'Eltávolítás',
        'refresh' => 'Frissítés',
        'view_module' => 'Modul Megtekintése: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => '":name" modul sikeresen bekapcsolva.',
        'module_disabled' => '":name" modul sikeresen kikapcsolva.',
        'module_not_found' => 'Modul nem található.',
        'modules_installed' => 'Modulok Telepítve',
        'modules_installed_body' => 'A következő modulok sikeresen telepítve: :names',
        'modules_skipped' => 'Modulok Kihagyva',
        'modules_skipped_body' => 'A következő modulok kihagyva: :names',
        'module_install_error' => 'Hiba történt a modul telepítése során.',
        'module_uninstalled' => 'Modul Eltávolítva',
        'module_uninstalled_body' => '":name" modul sikeresen eltávolítva.',
        'module_uninstall_error' => 'Hiba történt a modul eltávolítása során.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Állapot',
        'name' => 'Modul Név',
        'name_placeholder' => 'Keresés modul név szerint...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Bekapcsolva',
        'disabled' => 'Kikapcsolva',
        'cannot_be_disabled' => 'Ez a modul nem kapcsolható ki.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Modul ZIP fájl',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Elérhető Modulok',
        'available_description' => 'Összes telepített modul',
        'active' => 'Aktív Modulok',
        'active_description' => 'Jelenleg bekapcsolva',
        'disabled' => 'Letiltott Modulok',
        'disabled_description' => 'Jelenleg kikapcsolva',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modul név kötelező',
        'alias_required' => 'Modul alias kötelező',
        'alias_alpha_dash' => 'Modul alias csak betűket, számokat, kötőjeleket és aláhúzásjeleket tartalmazhat',
        'path_required' => 'Modul útvonal kötelező',
        'version_regex' => 'A verziónak a szemantikus verziószámozás formátumát kell követnie (pl.: 1.0.0)',
    ],

];
