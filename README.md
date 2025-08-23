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
- Supports `Spatie Data Transfer Objects` for typed module data.
- Configurable maximum ZIP upload size (default 20 MB).
- Uses `Sushi` in-memory storage for dynamic module queries.

---

## Installation

Install via Composer:

```bash
composer require alizharb/filament-module-manager
```
