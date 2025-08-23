<?php

/**
 * Filament Module Manager Configuration
 *
 * This configuration file is used by the Filament Module Manager plugin
 * for FilamentPHP 4. It handles navigation settings, module upload settings,
 * and other module manager-related configurations.
 *
 * @package FilamentModuleManager
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Navigation Settings
    |--------------------------------------------------------------------------
    |
    | Configure how the module manager appears in the Filament admin panel navigation.
    | You can enable/disable it, set its icon, label, group, and sort order.
    |
    */
    'navigation' => [
        /**
         * Whether to register the module manager in the navigation menu.
         *
         * @var bool
         */
        'register' => true,

        /**
         * Navigation sort order.
         *
         * Lower numbers appear first.
         *
         * @var int
         */
        'sort' => 100,

        /**
         * Heroicon name for the navigation icon.
         *
         * @var string
         */
        'icon' => 'heroicon-code-bracket',

        /**
         * Translation key for the navigation group.
         *
         * @var string
         */
        'group' => 'filament-module-manager::filament-module.navigation.group',

        /**
         * Translation key for the navigation label.
         *
         * @var string
         */
        'label' => 'filament-module-manager::filament-module.navigation.label',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Upload Settings
    |--------------------------------------------------------------------------
    |
    | Configure how modules can be uploaded via the Filament admin panel.
    | This includes disk storage, temporary directory, and max file size.
    |
    */
    'upload' => [
        /**
         * The disk where uploaded modules are temporarily stored.
         *
         * @var string
         */
        'disk' => 'public',

        /**
         * Temporary directory path for uploaded modules.
         *
         * @var string
         */
        'temp_directory' => 'temp/modules',

        /**
         * Maximum upload size in bytes.
         *
         * @var int
         */
        'max_size' => 20 * 1024 * 1024, // 20 MB
    ],

];
