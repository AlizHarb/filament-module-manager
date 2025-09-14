<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Rozšíření',
        'label' => 'Správce Modulů',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Název Modulu',
        'version' => 'Verze',
        'status' => 'Stav',
        'module_path' => 'Cesta k Modulu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Zobrazit',
        'enable' => 'Povolit',
        'disable' => 'Zakázat',
        'install' => 'Nainstalovat',
        'uninstall' => 'Odinstalovat',
        'refresh' => 'Obnovit',
        'view_module' => 'Zobrazit Modul: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Modul ":name" byl úspěšně povolen.',
        'module_disabled' => 'Modul ":name" byl úspěšně zakázán.',
        'module_not_found' => 'Modul nebyl nalezen.',
        'modules_installed' => 'Moduly Nainstalovány',
        'modules_installed_body' => 'Následující moduly byly úspěšně nainstalovány: :names',
        'modules_skipped' => 'Moduly Přeskočeny',
        'modules_skipped_body' => 'Následující moduly byly přeskočeny: :names',
        'module_install_error' => 'Při instalaci modulu došlo k chybě.',
        'module_uninstalled' => 'Modul Odinstalován',
        'module_uninstalled_body' => 'Modul ":name" byl úspěšně odinstalován.',
        'module_uninstall_error' => 'Při odinstalaci modulu došlo k chybě.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Stav',
        'name' => 'Název Modulu',
        'name_placeholder' => 'Hledat podle názvu modulu...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Povoleno',
        'disabled' => 'Zakázáno',
        'cannot_be_disabled' => 'Tento modul nelze zakázat.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'ZIP Soubor Modulu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Dostupné Moduly',
        'available_description' => 'Celkem nainstalovaných modulů',
        'active' => 'Aktivní Moduly',
        'active_description' => 'Aktuálně povoleno',
        'disabled' => 'Zakázané Moduly',
        'disabled_description' => 'Aktuálně zakázáno',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Název modulu je povinný',
        'alias_required' => 'Alias modulu je povinný',
        'alias_alpha_dash' => 'Alias modulu může obsahovat pouze písmena, čísla, pomlčky a podtržítka',
        'path_required' => 'Cesta k modulu je povinná',
        'version_regex' => 'Verze musí následovat formát sémantického verzování (např. 1.0.0)',
    ],

];
