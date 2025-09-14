<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensões',
        'label' => 'Gerenciador de Módulos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nome do Módulo',
        'version' => 'Versão',
        'status' => 'Status',
        'module_path' => 'Caminho do Módulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Visualizar',
        'enable' => 'Ativar',
        'disable' => 'Desativar',
        'install' => 'Instalar',
        'uninstall' => 'Desinstalar',
        'refresh' => 'Atualizar',
        'view_module' => 'Visualizar Módulo: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Módulo ":name" foi ativado com sucesso.',
        'module_disabled' => 'Módulo ":name" foi desativado com sucesso.',
        'module_not_found' => 'Módulo não encontrado.',
        'modules_installed' => 'Módulos Instalados',
        'modules_installed_body' => 'Os seguintes módulos foram instalados com sucesso: :names',
        'modules_skipped' => 'Módulos Ignorados',
        'modules_skipped_body' => 'Os seguintes módulos foram ignorados: :names',
        'module_install_error' => 'Ocorreu um erro durante a instalação do módulo.',
        'module_uninstalled' => 'Módulo desinstalado',
        'module_uninstalled_body' => 'Módulo ":name" foi desinstalado com sucesso.',
        'module_uninstall_error' => 'Ocorreu um erro ao desinstalar o módulo.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Status',
        'name' => 'Nome do Módulo',
        'name_placeholder' => 'Pesquisar por nome do módulo...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Ativado',
        'disabled' => 'Desativado',
        'cannot_be_disabled' => 'Este módulo não pode ser desativado.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Arquivo ZIP do Módulo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Módulos Disponíveis',
        'available_description' => 'Total de módulos instalados',
        'active' => 'Módulos Ativos',
        'active_description' => 'Atualmente ativados',
        'disabled' => 'Módulos Desativados',
        'disabled_description' => 'Atualmente desativados',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'O nome do módulo é obrigatório',
        'alias_required' => 'O alias do módulo é obrigatório',
        'alias_alpha_dash' => 'O alias do módulo só pode conter letras, números, traços e sublinhados',
        'path_required' => 'O caminho do módulo é obrigatório',
        'version_regex' => 'A versão deve seguir o formato de versionamento semântico (exemplo: 1.0.0)',
    ],

];
