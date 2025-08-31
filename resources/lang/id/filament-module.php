<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Ekstensi',
        'label' => 'Manajer Modul',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nama Modul',
        'version' => 'Versi',
        'status' => 'Status',
        'module_path' => 'Path Modul',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Lihat',
        'enable' => 'Aktifkan',
        'disable' => 'Nonaktifkan',
        'install' => 'Instal',
        'uninstall' => 'Uninstall',
        'refresh' => 'Refresh',
        'view_module' => 'Lihat Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modul ":name" berhasil diaktifkan.',
        'module_disabled' => 'Modul ":name" berhasil dinonaktifkan.',
        'module_not_found' => 'Modul tidak ditemukan.',
        'modules_installed' => 'Modul Terinstall',
        'modules_installed_body' => 'Modul berikut berhasil diinstall: :names',
        'modules_skipped' => 'Modul Dilewati',
        'modules_skipped_body' => 'Modul berikut dilewati: :names',
        'module_install_error' => 'Terjadi kesalahan saat menginstall modul.',
        'module_uninstalled' => 'Modul Diuninstall',
        'module_uninstalled_body' => 'Modul ":name" berhasil diuninstall.',
        'module_uninstall_error' => 'Terjadi kesalahan saat menguninstall modul.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Nama Modul',
        'name_placeholder' => 'Cari berdasarkan nama modul...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Aktif',
        'disabled' => 'Nonaktif',
        'cannot_be_disabled' => 'Modul ini tidak dapat dinonaktifkan.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'File ZIP Modul',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Modul Tersedia',
        'available_description' => 'Total modul terinstall',
        'active' => 'Modul Aktif',
        'active_description' => 'Saat ini aktif',
        'disabled' => 'Modul Nonaktif',
        'disabled_description' => 'Saat ini nonaktif',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Nama modul wajib diisi',
        'alias_required' => 'Alias modul wajib diisi',
        'alias_alpha_dash' => 'Alias modul hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah',
        'path_required' => 'Path modul wajib diisi',
        'version_regex' => 'Versi harus mengikuti format semantic versioning (contoh: 1.0.0)',
    ],

];