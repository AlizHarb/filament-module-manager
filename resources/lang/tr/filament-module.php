<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Eklentiler',
        'label' => 'Modül Yöneticisi',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Modül Adı',
        'version' => 'Sürüm',
        'status' => 'Durum',
        'module_path' => 'Modül Yolu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Görüntüle',
        'enable' => 'Etkinleştir',
        'disable' => 'Devre Dışı Bırak',
        'install' => 'Yükle',
        'uninstall' => 'Kaldır',
        'refresh' => 'Yenile',
        'view_module' => 'Modülü Görüntüle: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => '":name" modülü başarıyla etkinleştirildi.',
        'module_disabled' => '":name" modülü başarıyla devre dışı bırakıldı.',
        'module_not_found' => 'Modül bulunamadı.',
        'modules_installed' => 'Modüller Yüklendi',
        'modules_installed_body' => 'Aşağıdaki modüller başarıyla yüklendi: :names',
        'modules_skipped' => 'Modüller Atlandı',
        'modules_skipped_body' => 'Aşağıdaki modüller atlandı: :names',
        'module_install_error' => 'Modül yükleme sırasında bir hata oluştu.',
        'module_uninstalled' => 'Modül Kaldırıldı',
        'module_uninstalled_body' => '":name" modülü başarıyla kaldırıldı.',
        'module_uninstall_error' => 'Modül kaldırma sırasında bir hata oluştu.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Durum',
        'name' => 'Modül Adı',
        'name_placeholder' => 'Modül adıyla ara...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Etkin',
        'disabled' => 'Devre Dışı',
        'cannot_be_disabled' => 'Bu modül devre dışı bırakılamaz.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Modül ZIP Dosyası',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Mevcut Modüller',
        'available_description' => 'Toplam yüklü modüller',
        'active' => 'Aktif Modüller',
        'active_description' => 'Şu anda etkin',
        'disabled' => 'Devre Dışı Modüller',
        'disabled_description' => 'Şu anda devre dışı',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Modül adı gereklidir',
        'alias_required' => 'Modül takma adı gereklidir',
        'alias_alpha_dash' => 'Modül takma adı yalnızca harfler, rakamlar, tireler ve alt çizgiler içerebilir',
        'path_required' => 'Modül yolu gereklidir',
        'version_regex' => 'Sürüm anlamsal sürüm formatını takip etmelidir (örnek: 1.0.0)',
    ],

];
