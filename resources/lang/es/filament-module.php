<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensiones',
        'label' => 'Administrador de Módulos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nombre del Módulo',
        'version' => 'Versión',
        'status' => 'Estado',
        'module_path' => 'Ruta del Módulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Ver',
        'enable' => 'Habilitar',
        'disable' => 'Deshabilitar',
        'install' => 'Instalar',
        'uninstall' => 'Desinstalar',
        'refresh' => 'Actualizar',
        'view_module' => 'Ver Módulo: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'El módulo ":name" ha sido habilitado correctamente.',
        'module_disabled' => 'El módulo ":name" ha sido deshabilitado correctamente.',
        'module_not_found' => 'Módulo no encontrado.',
        'modules_installed' => 'Módulos Instalados',
        'modules_installed_body' => 'Los siguientes módulos se han instalado correctamente: :names',
        'modules_skipped' => 'Módulos Omitidos',
        'modules_skipped_body' => 'Se omitieron los siguientes módulos: :names',
        'module_install_error' => 'Ocurrió un error durante la instalación del módulo.',
        'module_uninstalled' => 'Módulo desinstalado',
        'module_uninstalled_body' => 'El módulo ":name" ha sido desinstalado correctamente.',
        'module_uninstall_error' => 'Ocurrió un error al desinstalar el módulo.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Estado',
        'name' => 'Nombre del Módulo',
        'name_placeholder' => 'Buscar por nombre de módulo...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Habilitado',
        'disabled' => 'Deshabilitado',
        'cannot_be_disabled' => 'Este módulo no puede ser deshabilitado.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Archivo ZIP del Módulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Módulos Disponibles',
        'available_description' => 'Total de módulos instalados',
        'active' => 'Módulos Activos',
        'active_description' => 'Actualmente habilitados',
        'disabled' => 'Módulos Deshabilitados',
        'disabled_description' => 'Actualmente deshabilitados',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'El nombre del módulo es obligatorio',
        'alias_required' => 'El alias del módulo es obligatorio',
        'alias_alpha_dash' => 'El alias del módulo solo puede contener letras, números, guiones y guiones bajos',
        'path_required' => 'La ruta del módulo es obligatoria',
        'version_regex' => 'La versión debe seguir el formato de versionado semántico (ejemplo: 1.0.0)',
    ],

];
