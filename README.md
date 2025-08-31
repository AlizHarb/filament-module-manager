# 🚀 Filament Module Manager

<div align="center">
    <img src="https://banners.beyondco.de/Filament%20Module%20Manager.png?theme=light&packageManager=composer+require&packageName=alizharb%2Ffilament-module-manager&pattern=architect&style=style_1&description=Elegant+module+management+for+Filament+admin+panels&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" alt="Filament Module Manager">
</div>

<div align="center">

[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge)](LICENSE)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/alizharb/filament-module-manager.svg?style=for-the-badge&color=orange)](https://packagist.org/packages/alizharb/filament-module-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/alizharb/filament-module-manager.svg?style=for-the-badge&color=green)](https://packagist.org/packages/alizharb/filament-module-manager)
[![GitHub Stars](https://img.shields.io/github/stars/AlizHarb/filament-module-manager.svg?style=for-the-badge&color=yellow)](https://github.com/AlizHarb/filament-module-manager/stargazers)
[![PHP Version](https://img.shields.io/packagist/php-v/alizharb/filament-module-manager.svg?style=for-the-badge&color=purple)](https://packagist.org/packages/alizharb/filament-module-manager)

</div>

<p align="center">
    <strong>A powerful Filament v4 plugin for managing Laravel application modules with ease</strong><br>
    Built on top of <a href="https://nwidart.com/laravel-modules">Nwidart/laravel-modules</a> with modern admin interface
</p>

---

## ✨ Features

<table>
<tr>
<td width="50%">

### 🎯 **Core Features**

- 🔧 **Full Filament v4 Compatibility**
- 📦 **Module CRUD Operations** (View, Enable, Disable, Uninstall)
- 📤 **ZIP Upload Installation** with validation
- 🏷️ **Multi-Module Package Support** via `package.json`
- 📊 **Dashboard Widget** with statistics
- 🌍 **Multi-Language Support** (20+ languages)
- ⚙️ **Configurable Navigation** (icon, group, sort order)
- 🆕 **Configurable Widget** (enable, dashboard, page)
- 🆕 **GitHub Repository Installation** – Install modules directly from GitHub (branch fallback included)

</td>
<td width="50%">

### 🛠️ **Technical Features**

- ✅ **Smart Module Validation** (`module.json`, `composer.json`)
- 🔔 **Rich Notifications** (Success, Error, Warnings)
- 📋 **Spatie DTO Integration** for type safety
- 🗄️ **Sushi In-Memory Storage** for dynamic queries
- ⚙️ **Configurable Upload Limits** (default: 20MB)
- 🆕 **Accurate Module Naming** Reads name directly from module.json for ZIP and GitHub installs.
- 🆕 **Automatic Folder Renaming** Module directories are automatically renamed to match module.json.
- 🆕 **Metadata Handling** Reads description and version from module.json for accurate display and notifications.
- 🆕 **GitHub Branch Fallback** Installs from main branch, automatically falling back to master if needed.
- 🆕 **Full Cache & Config Clearing** Clears config, route, and cache after install/uninstall for immediate availability.

</td>
</tr>
</table>

## 🎬 Preview

> 📸 _Screenshots and demo GIFs will be added soon_

## 📋 Requirements

| Requirement                                                                                         | Version | Status |
| --------------------------------------------------------------------------------------------------- | ------- | ------ |
| ![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)            | 8.2+    | ✅     |
| ![Laravel](https://img.shields.io/badge/Laravel-10+-FF2D20?style=flat&logo=laravel&logoColor=white) | 10+     | ✅     |
| ![Filament](https://img.shields.io/badge/Filament-v4+-F59E0B?style=flat&logo=php&logoColor=white)   | v4+     | ✅     |

**Dependencies:**

- [Nwidart Laravel Modules](https://nwidart.com/laravel-modules) - Module foundation
- [Spatie Data Transfer Objects](https://github.com/spatie/laravel-data) - Type-safe data handling

## ⚡ Quick Installation

### Step 1: Install via Composer

```bash
composer require alizharb/filament-module-manager
```

### Step 2: Register the Plugin

Add to your `AdminPanelProvider`:

```php
use Alizharb\FilamentModuleManager\FilamentModuleManagerPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other configurations
        ->plugin(FilamentModuleManagerPlugin::make());
}
```

### Step 3: Publish Configuration (Optional)

```bash
# Publish config file
php artisan vendor:publish --tag="filament-module-manager-config" --force

# Publish translations
php artisan vendor:publish --tag="filament-module-manager-translations"
```

## 🎯 Usage Guide

### 📱 **Module Management Page**

Navigate to **Module Manager** from your Filament admin sidebar to:

- 📋 View all installed modules
- ✅ Enable/disable modules
- 🗑️ Uninstall unwanted modules
- 📤 Upload new modules via ZIP

### 📊 **Dashboard Widget**

The overview widget displays:

- 🟢 **Active Modules** count
- 🔴 **Disabled Modules** count
- 📈 **Total Modules** installed

### 📦 **Module Installation**

#### **Single Module Upload**

1. Prepare your module as a ZIP file
2. Ensure `module.json` exists in the module root
3. Upload through the admin interface
4. Enable the module after installation

#### **Multi-Module Package Upload**

1. Create a ZIP containing multiple modules
2. Add a `package.json` in the root with module paths:
   ```json
   {
     "name": "my-module-collection",
     "version": "1.0.0",
     "modules": ["Modules/Blog", "Modules/Shop"]
   }
   ```
3. Upload the package ZIP file
4. All modules will be extracted and available for management

#### **Module Structure Requirements**

```
MyModule/
├── module.json          # Required module configuration
├── composer.json        # Optional composer configuration
├── Config/
├── Http/
├── resources/
└── ...
```

## ⚙️ Configuration

The published configuration file (`config/filament-module-manager.php`) allows you to customize various aspects:

### 🧭 **Navigation Settings**

```php
'navigation' => [
    'register' => true,                    // Show in navigation menu
    'sort' => 100,                        // Navigation order
    'icon' => 'heroicon-o-code-bracket',  // Navigation icon
    'group' => 'filament-module-manager::filament-module.navigation.group',
    'label' => 'filament-module-manager::filament-module.navigation.label',
],
```

### 📤 **Upload Settings**

```php
'upload' => [
    'disk' => 'public',                   // Storage disk
    'temp_directory' => 'temp/modules',   // Temporary upload path
    'max_size' => 20 * 1024 * 1024,      // Max file size (20MB)
],
```

### 🌍 **Multi-Language Support**

The package supports multiple languages through translation files:

- English (default)
- Arabic
- Spanish
- French
- German
- And more...

Publish translations and customize them:

```bash
php artisan vendor:publish --tag="filament-module-manager-translations"
```

### 📦 **Package Module Support**

The plugin supports multi-module packages via `package.json`:

```json
{
  "name": "my-module-package",
  "version": "1.0.0",
  "modules": ["Modules/Blog", "Modules/Shop", "Modules/User"]
}
```

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. 🍴 Fork the repository
2. 🌿 Create your feature branch (`git checkout -b feature/amazing-feature`)
3. ✅ Commit your changes (`git commit -m 'Add amazing feature'`)
4. 📤 Push to the branch (`git push origin feature/amazing-feature`)
5. 🎯 Open a Pull Request

### Development Setup

```bash
# Clone the repository
git clone https://github.com/AlizHarb/filament-module-manager.git

# Install dependencies
composer install

# Run tests
composer test
```

## 💖 Sponsor This Project

If this package helps you, consider sponsoring its development:

<div align="center">

[![Sponsor on GitHub](https://img.shields.io/badge/Sponsor-GitHub-red?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/AlizHarb)

</div>

Your support helps maintain and improve this package for the entire community! 🙏

## 🐛 Issues & Support

- 🐛 **Bug Reports**: [Create an issue](https://github.com/AlizHarb/filament-module-manager/issues/new?template=bug_report.md)
- 💡 **Feature Requests**: [Request a feature](https://github.com/AlizHarb/filament-module-manager/issues/new?template=feature_request.md)
- 💬 **Discussions**: [Join the discussion](https://github.com/AlizHarb/filament-module-manager/discussions)

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Filament PHP](https://filamentphp.com/) - For the amazing admin panel framework
- [Nwidart Laravel Modules](https://nwidart.com/laravel-modules) - For solid module foundation
- [Spatie](https://spatie.be/) - For excellent Laravel packages
- All contributors and supporters 🎉

---

<div align="center">
    <p>
        <strong>Made with ❤️ by <a href="https://github.com/AlizHarb">Ali Harb</a></strong><br>
        <sub>Star ⭐ this repository if it helped you!</sub>
    </p>
</div>
