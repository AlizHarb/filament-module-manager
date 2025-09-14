<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Rozszerzenia',
        'label' => 'Menedżer Modułów',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nazwa Modułu',
        'version' => 'Wersja',
        'status' => 'Status',
        'module_path' => 'Ścieżka Modułu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Podgląd',
        'enable' => 'Włącz',
        'disable' => 'Wyłącz',
        'install' => 'Zainstaluj',
        'uninstall' => 'Odinstaluj',
        'refresh' => 'Odśwież',
        'view_module' => 'Podgląd Modułu: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Moduł ":name" został pomyślnie włączony.',
        'module_disabled' => 'Moduł ":name" został pomyślnie wyłączony.',
        'module_not_found' => 'Moduł nie znaleziony.',
        'modules_installed' => 'Moduły Zainstalowane',
        'modules_installed_body' => 'Następujące moduły zostały pomyślnie zainstalowane: :names',
        'modules_skipped' => 'Moduły Pominięte',
        'modules_skipped_body' => 'Następujące moduły zostały pominięte: :names',
        'module_install_error' => 'Wystąpił błąd podczas instalacji modułu.',
        'module_uninstalled' => 'Moduł Odinstalowany',
        'module_uninstalled_body' => 'Moduł ":name" został pomyślnie odinstalowany.',
        'module_uninstall_error' => 'Wystąpił błąd podczas odinstalowywania modułu.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Nazwa Modułu',
        'name_placeholder' => 'Szukaj według nazwy modułu...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Włączony',
        'disabled' => 'Wyłączony',
        'cannot_be_disabled' => 'Ten moduł nie może być wyłączony.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Plik ZIP Modułu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Dostępne Moduły',
        'available_description' => 'Łącznie zainstalowanych modułów',
        'active' => 'Aktywne Moduły',
        'active_description' => 'Obecnie włączone',
        'disabled' => 'Wyłączone Moduły',
        'disabled_description' => 'Obecnie wyłączone',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Nazwa modułu jest wymagana',
        'alias_required' => 'Alias modułu jest wymagany',
        'alias_alpha_dash' => 'Alias modułu może zawierać tylko litery, cyfry, myślniki i podkreślenia',
        'path_required' => 'Ścieżka modułu jest wymagana',
        'version_regex' => 'Wersja musi być w formacie semantycznego wersjonowania (np. 1.0.0)',
    ],

];
