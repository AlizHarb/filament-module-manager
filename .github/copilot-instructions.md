# Copilot Instructions for Filament Module Manager

## Project Overview

- **Purpose:** Elegant module management for Filament v4 admin panels in Laravel, built atop `nwidart/laravel-modules`.
- **Key Features:** Module CRUD, ZIP/GitHub install, dashboard widget, multi-language, rich notifications, Spatie DTO, Sushi in-memory storage, automatic cache/config clearing.

## Architecture & Key Components

- **Service Provider:** `src/FilamentModuleManagerServiceProvider.php` – Registers core services and plugin.
- **Plugin Entry:** `src/FilamentModuleManagerPlugin.php` – Main Filament plugin registration.
- **Data Layer:** `src/Data/` – DTOs for module and install result data.
- **Models:** `src/Models/Module.php` – Represents a module, integrates with Nwidart modules.
- **Pages:** `src/Pages/ModuleManager.php` – Filament admin page for module management.
- **Services:** `src/Services/ModuleManagerService.php` – Core logic for module CRUD, install, validation, notifications.
- **Widgets:** `src/Widgets/ModulesOverview.php` – Dashboard widget for module stats.
- **Config:** `config/filament-module-manager.php` – Navigation, upload, and widget settings.
- **Translations:** `resources/lang/` – 20+ language support for UI strings.
- **Views:** `resources/views/module-manager.blade.php` – Main admin UI.

## Developer Workflows

- **Install dependencies:** `composer install`
- **Run tests:** `composer test`
- **Publish config/translations:**
  - `php artisan vendor:publish --tag="filament-module-manager-config" --force`
  - `php artisan vendor:publish --tag="filament-module-manager-translations"`
- **Register plugin:** Add `FilamentModuleManagerPlugin::make()` to your Filament panel provider.
- **Debugging:** Use Filament's built-in debug tools and Laravel logs. Module install/uninstall triggers cache/config/route clearing automatically.

## Project-Specific Patterns

- **Module ZIP Upload:** Requires `module.json` in root. Multi-module ZIPs need a `package.json` listing module paths.
- **GitHub Install:** Installs from main branch, falls back to master if needed. Reads metadata from `module.json`.
- **Notifications:** All CRUD/install actions use Filament notifications for success/error/warning.
- **DTO Usage:** All data passed between services/pages uses Spatie DTOs for type safety.
- **Sushi Storage:** Used for fast, in-memory queries of module data.
- **Automatic Folder Renaming:** Module directories are renamed to match `module.json` name after install.
- **Cache/Config Clearing:** After install/uninstall, runs Laravel's cache/config/route clear commands for immediate effect.

## Integration Points

- **Nwidart/laravel-modules:** All module operations use this package as backend.
- **Spatie/laravel-data:** DTOs for all data transfer.
- **Filament v4:** All UI, notifications, widgets, and admin pages.

## Conventions

- **Translations:** Add new languages in `resources/lang/` and publish with artisan.
- **Config:** All customizations via `config/filament-module-manager.php`.
- **Module Structure:**
  ```
  MyModule/
  ├── module.json
  ├── composer.json
  ├── Config/
  ├── Http/
  ├── resources/
  └── ...
  ```
- **Testing:** Feature tests in `tests/Feature/ModuleManagerTest.php`.

## Example: Registering the Plugin

```php
use Alizharb\FilamentModuleManager\FilamentModuleManagerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentModuleManagerPlugin::make());
}
```

---

**For unclear or missing sections, please provide feedback so instructions can be improved.**
