<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Extensions',
        'label' => 'Gestionnaire de Modules',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Nom du Module',
        'version' => 'Version',
        'status' => 'Statut',
        'module_path' => 'Chemin du Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Voir',
        'enable' => 'Activer',
        'disable' => 'Désactiver',
        'install' => 'Installer',
        'uninstall' => 'Désinstaller',
        'refresh' => 'Actualiser',
        'view_module' => 'Voir le Module: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Le module ":name" a été activé avec succès.',
        'module_disabled' => 'Le module ":name" a été désactivé avec succès.',
        'module_not_found' => 'Module non trouvé.',
        'modules_installed' => 'Modules Installés',
        'modules_installed_body' => 'Les modules suivants ont été installés avec succès: :names',
        'modules_skipped' => 'Modules Ignorés',
        'modules_skipped_body' => 'Les modules suivants ont été ignorés: :names',
        'module_install_error' => 'Une erreur s\'est produite lors de l\'installation du module.',
        'module_uninstalled' => 'Module désinstallé',
        'module_uninstalled_body' => 'Le module ":name" a été désinstallé avec succès.',
        'module_uninstall_error' => 'Une erreur s\'est produite lors de la désinstallation du module.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Statut',
        'name' => 'Nom du Module',
        'name_placeholder' => 'Rechercher par nom de module...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Activé',
        'disabled' => 'Désactivé',
        'cannot_be_disabled' => 'Ce module ne peut pas être désactivé.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Fichier ZIP du Module',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Modules Disponibles',
        'available_description' => 'Total des modules installés',
        'active' => 'Modules Actifs',
        'active_description' => 'Actuellement activés',
        'disabled' => 'Modules Désactivés',
        'disabled_description' => 'Actuellement désactivés',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Le nom du module est requis',
        'alias_required' => 'L\'alias du module est requis',
        'alias_alpha_dash' => 'L\'alias du module ne peut contenir que des lettres, des chiffres, des tirets et des traits de soulignement',
        'path_required' => 'Le chemin du module est requis',
        'version_regex' => 'La version doit suivre le format de version sémantique (exemple: 1.0.0)',
    ],

];