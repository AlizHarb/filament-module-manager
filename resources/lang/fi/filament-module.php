<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modules Navigation
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'group' => 'Laajennukset',
        'label' => 'Moduulien Hallinta',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'module_name' => 'Moduulin Nimi',
        'version' => 'Versio',
        'status' => 'Tila',
        'module_path' => 'Moduulin Polku',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Actions
    |--------------------------------------------------------------------------
    */
    'actions' => [
        'view' => 'Näytä',
        'enable' => 'Ota Käyttöön',
        'disable' => 'Poista Käytöstä',
        'install' => 'Asenna',
        'uninstall' => 'Poista',
        'refresh' => 'Päivitä',
        'view_module' => 'Näytä Moduuli: :name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'module_enabled' => 'Moduuli ":name" on otettu käyttöön.',
        'module_disabled' => 'Moduuli ":name" on poistettu käytöstä.',
        'module_not_found' => 'Moduulia ei löytynyt.',
        'modules_installed' => 'Moduulit Asennettu',
        'modules_installed_body' => 'Seuraavat moduulit on asennettu: :names',
        'modules_skipped' => 'Moduulit Ohitettu',
        'modules_skipped_body' => 'Seuraavat moduulit on ohitettu: :names',
        'module_install_error' => 'Moduulin asennuksessa tapahtui virhe.',
        'module_uninstalled' => 'Moduuli Poistettu',
        'module_uninstalled_body' => 'Moduuli ":name" on poistettu.',
        'module_uninstall_error' => 'Moduulin poistossa tapahtui virhe.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        'status' => 'Tila',
        'name' => 'Moduulin Nimi',
        'name_placeholder' => 'Hae moduulin nimellä...',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Statuses
    |--------------------------------------------------------------------------
    */
    'status' => [
        'enabled' => 'Käytössä',
        'disabled' => 'Poissa Käytöstä',
        'cannot_be_disabled' => 'Tätä moduulia ei voi poistaa käytöstä.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Form Fields
    |--------------------------------------------------------------------------
    */
    'form' => [
        'zip_file' => 'Moduulin ZIP-tiedosto',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Overview Stats
    |--------------------------------------------------------------------------
    */
    'overview' => [
        'available' => 'Saatavilla Olevat Moduulit',
        'available_description' => 'Asennettujen moduulien kokonaismäärä',
        'active' => 'Aktiiviset Moduulit',
        'active_description' => 'Tällä hetkellä käytössä',
        'disabled' => 'Poistetut Moduulit Käytöstä',
        'disabled_description' => 'Tällä hetkellä poissa käytöstä',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Validation
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'name_required' => 'Moduulin nimi vaaditaan',
        'alias_required' => 'Moduulin aliaksen nimi vaaditaan',
        'alias_alpha_dash' => 'Moduulin aliaksen nimi voi sisältää vain kirjaimia, numeroita, viivoja ja alaviivoja',
        'path_required' => 'Moduulin polku vaaditaan',
        'version_regex' => 'Version on noudatettava semanttista versionhallintamuotoa (esim. 1.0.0)',
    ],

];