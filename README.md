# Filament Module Manager

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A **Filament v4 plugin** for managing Laravel application modules using [Nwidart/laravel-modules](https://nwidart.com/laravel-modules).  
It provides a **Module Manager page**, **Modules overview widget**, and easy **module installation/uninstallation** via ZIP uploads.

---

## Features

- Fully compatible with **Filament v4**.
- View, enable, disable, and uninstall modules from a clean admin interface.
- Install modules from ZIP files (supports multi-module packages with `package.json`).
- Module overview dashboard widget with active, disabled, and total module counts.
- Integrates with **Nwidart Modules** for consistent module management.
- Validates modules with `module.json` or `composer.json`.
- Notifications for module operations (success, skipped, errors).
- Supports **Spatie Data Transfer Objects** for typed module data.
- Configurable maximum ZIP upload size (default 20 MB).
- Uses **Sushi** in-memory storage for dynamic module queries.

---

## Installation

Install via Composer:

```bash
composer require alizharb/filament-module-manager
```

## Setup

### Add the Plugin to Your Admin Panel

Add the plugin in your `AdminPanelProvider`:

```php
use Alizharb\FilamentModuleManager\FilamentModuleManagerPlugin;

$panel
    // ...
    ->plugin(FilamentModuleManagerPlugin::make());
```

> **Note:** This will override your existing config file.

### Publish Config File

```bash
php artisan vendor:publish --tag="filament-module-manager-config" --force
```

### Publish Translations

```bash
php artisan vendor:publish --tag="filament-module-manager-translations"
```

## Usage

- Access the **Module Manager** from your Filament admin panel sidebar.
- Use the dashboard widget to see module statistics at a glance.
- Upload ZIP files for new modules and manage existing modules directly.
- Enable or disable modules as needed for your application.

## Requirements

- Laravel 12+
- PHP 8.2+
- Filament v4+
- Nwidart Laravel Modules
- Spatie Data Transfer Objects (for typed module data)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
