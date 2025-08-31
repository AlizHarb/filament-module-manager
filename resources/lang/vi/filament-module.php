<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Tiện ích mở rộng',
        'label' => 'Quản lý Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Tên Module',
        'version' => 'Phiên bản',
        'status' => 'Trạng thái',
        'module_path' => 'Đường dẫn Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Xem',
        'enable' => 'Kích hoạt',
        'disable' => 'Vô hiệu hóa',
        'install' => 'Cài đặt',
        'uninstall' => 'Gỡ cài đặt',
        'refresh' => 'Làm mới',
        'view_module' => 'Xem Module: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Module ":name" đã được kích hoạt thành công.',
        'module_disabled' => 'Module ":name" đã được vô hiệu hóa thành công.',
        'module_not_found' => 'Không tìm thấy module.',
        'modules_installed' => 'Module Đã Cài Đặt',
        'modules_installed_body' => 'Các module sau đã được cài đặt thành công: :names',
        'modules_skipped' => 'Module Đã Bỏ Qua',
        'modules_skipped_body' => 'Các module sau đã bị bỏ qua: :names',
        'module_install_error' => 'Đã xảy ra lỗi trong quá trình cài đặt module.',
        'module_uninstalled' => 'Module Đã Gỡ Cài Đặt',
        'module_uninstalled_body' => 'Module ":name" đã được gỡ cài đặt thành công.',
        'module_uninstall_error' => 'Đã xảy ra lỗi khi gỡ cài đặt module.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Trạng thái',
        'name' => 'Tên Module',
        'name_placeholder' => 'Tìm kiếm theo tên module...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Đã kích hoạt',
        'disabled' => 'Đã vô hiệu hóa',
        'cannot_be_disabled' => 'Module này không thể bị vô hiệu hóa.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'File ZIP Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Module Có Sẵn',
        'available_description' => 'Tổng số module đã cài đặt',
        'active' => 'Module Hoạt Động',
        'active_description' => 'Hiện đang kích hoạt',
        'disabled' => 'Module Vô Hiệu Hóa',
        'disabled_description' => 'Hiện đang vô hiệu hóa',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Tên module là bắt buộc',
        'alias_required' => 'Bí danh module là bắt buộc',
        'alias_alpha_dash' => 'Bí danh module chỉ có thể chứa chữ cái, số, dấu gạch ngang và gạch dưới',
        'path_required' => 'Đường dẫn module là bắt buộc',
        'version_regex' => 'Phiên bản phải tuân theo định dạng semantic versioning (ví dụ: 1.0.0)',
    ],

];