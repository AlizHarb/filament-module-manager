# 🚀 Filament Module Manager

<div align="center">
    <img src="https://banners.beyondco.de/Filament%20Module%20Manager.png?theme=light&packageManager=composer+require&packageName=alizharb%2Ffilament-module-manager&pattern=architect&style=style_1&description=Enterprise-grade+module+management+for+Filament+v4&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" alt="Filament Module Manager">
</div>

<div align="center">

[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge)](LICENSE)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/alizharb/filament-module-manager.svg?style=for-the-badge&color=orange)](https://packagist.org/packages/alizharb/filament-module-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/alizharb/filament-module-manager.svg?style=for-the-badge&color=green)](https://packagist.org/packages/alizharb/filament-module-manager)
[![GitHub Stars](https://img.shields.io/github/stars/AlizHarb/filament-module-manager.svg?style=for-the-badge&color=yellow)](https://github.com/AlizHarb/filament-module-manager/stargazers)
[![PHP Version](https://img.shields.io/packagist/php-v/alizharb/filament-module-manager.svg?style=for-the-badge&color=purple)](https://packagist.org/packages/alizharb/filament-module-manager)

</div>

<p align="center">
    <strong>Enterprise-grade module management for Filament v4 & v5 admin panels</strong><br>
    Complete lifecycle management with dependencies, updates, backups, and health monitoring<br>
    Built on <a href="https://nwidart.com/laravel-modules">Nwidart/laravel-modules</a>
</p>

---

## 📖 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Quick Start](#-quick-start)
- [Core Features](#-core-features)
- [Enterprise Features](#-enterprise-features)
- [Configuration](#️-configuration)
- [Usage Examples](#-usage-examples)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🎯 Core Module Management

- **📦 Full CRUD Operations** - View, install, enable, disable, and uninstall modules
- **📤 Multiple Installation Methods** - ZIP upload, GitHub repository, or local path
- **🏷️ Multi-Module Packages** - Install multiple modules from a single package
- **📊 Dashboard Widget** - Real-time statistics and module overview
- **🌍 Multi-Language Support** - 20+ languages included
- **⚙️ Highly Configurable** - Customize navigation, uploads, and behavior

### 🚀 Enterprise Features (v2.0)

<table>
<tr>
<td width="50%">

#### 🔗 **Dependency Management**

- Automatic dependency validation
- Circular dependency detection
- Version constraint support
- Prevents breaking changes

#### 🔄 **Update System**

- GitHub releases integration
- One-click updates
- Changelog display
- Automatic backups before update

#### 💾 **Backup & Restore**

- Automatic backups (updates/uninstalls)
- One-click restore
- Retention management
- Size tracking

</td>
<td width="50%">

#### 🏥 **Health Monitoring**

- Automated integrity checks
- File validation
- Dependency verification
- Health scoring (0-100)

#### 📝 **Audit Logging**

- Complete operation trail
- User tracking
- IP & timestamp logging
- Success/failure tracking

#### 🐙 **GitHub Integration**

- Install from releases/tags
- OAuth token support
- Rate limit management
- Branch fallback (main/master)

</td>
</tr>
</table>

---

## 📋 Requirements

| Requirement                                                                                         | Version | Status |
| --------------------------------------------------------------------------------------------------- | ------- | ------ |
| ![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)            | 8.3+    | ✅     |
| ![Laravel](https://img.shields.io/badge/Laravel-10+-FF2D20?style=flat&logo=laravel&logoColor=white) | 10+     | ✅     |
| ![Filament](https://img.shields.io/badge/Filament-v4+-F59E0B?style=flat&logo=php&logoColor=white)   | v4/v5   | ✅     |

**Dependencies:**

- [Nwidart Laravel Modules](https://nwidart.com/laravel-modules) - Module foundation
- [Spatie Laravel Data](https://github.com/spatie/laravel-data) - Type-safe DTOs

---

## ⚡ Installation

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
        ->plugin(FilamentModuleManagerPlugin::make());
}
```

### Step 3: Publish Assets (Optional)

```bash
# Publish configuration
php artisan vendor:publish --tag="filament-module-manager-config"

# Publish translations
php artisan vendor:publish --tag="filament-module-manager-translations"
```

---

## 🎯 Quick Start

### Access the Module Manager

Navigate to **Module Manager** in your Filament admin sidebar.

### Install Your First Module

**Option 1: Upload ZIP**

1. Prepare module as ZIP with `module.json` in root
2. Click "Upload Module" button
3. Select ZIP file (max 20MB by default)
4. Module installs and appears in list

**Option 2: Install from GitHub**

1. Add repository to module's `module.json`:
   ```json
   {
     "name": "Blog",
     "repository": "username/blog-module"
   }
   ```
2. Use GitHub installation feature
3. Module downloads and installs automatically

### Enable/Disable Modules

- Toggle module status with one click
- Automatic dependency validation
- Cache clearing after changes

---

## 🎯 Core Features

### 📦 Module Installation

#### ZIP Upload Installation

Upload modules as ZIP files with automatic validation:

```
MyModule.zip
└── MyModule/
    ├── module.json      # Required
    ├── composer.json    # Optional
    ├── Config/
    ├── Http/
    └── resources/
```

**Features:**

- ✅ Automatic module.json validation
- ✅ Folder name correction
- ✅ Metadata extraction
- ✅ Duplicate detection
- ✅ Size limit enforcement

#### Multi-Module Package Installation

Install multiple modules from a single package:

```json
// package.json in ZIP root
{
  "name": "my-module-collection",
  "version": "1.0.0",
  "modules": ["Modules/Blog", "Modules/Shop", "Modules/User"]
}
```

Upload the package ZIP and all modules install automatically.

#### GitHub Repository Installation

Install directly from GitHub repositories:

```json
// module.json
{
  "name": "Blog",
  "version": "1.0.0",
  "repository": "username/blog-module"
}
```

**Features:**

- ✅ Branch fallback (main → master)
- ✅ OAuth token support for private repos
- ✅ Automatic extraction and installation

### 📊 Dashboard Widget

Real-time module statistics:

- 🟢 **Active Modules** - Currently enabled
- 🔴 **Disabled Modules** - Installed but inactive
- 📈 **Total Modules** - All installed modules

Configure widget placement:

```php
'widget' => [
    'enabled' => true,
    'show_on_dashboard' => true,
    'show_on_module_page' => true,
],
```

---

## 🚀 Enterprise Features

### 1. 🔗 Module Dependencies Management

Automatically manage module dependencies with validation and conflict prevention.

#### Define Dependencies

In your `module.json`:

```json
{
  "name": "Blog",
  "version": "1.0.0",
  "requires": {
    "User": "^1.0",
    "Media": "~2.0",
    "Core": "*"
  }
}
```

#### Version Constraints

| Constraint | Meaning | Example          |
| ---------- | ------- | ---------------- |
| `^1.0`     | Caret   | `>=1.0.0 <2.0.0` |
| `~2.0`     | Tilde   | `>=2.0.0 <2.1.0` |
| `*`        | Any     | Any version      |
| `1.5.0`    | Exact   | Exactly 1.5.0    |

#### Features

- ✅ **Automatic Validation** - Checks dependencies before install/enable
- ✅ **Circular Detection** - Prevents circular dependency loops
- ✅ **Dependent Protection** - Can't disable modules with active dependents
- ✅ **Dependency Tree** - Visual representation of relationships
- ✅ **Installation Order** - Topological sorting for correct order

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\ModuleDependencyService;

$service = app(ModuleDependencyService::class);

// Validate dependencies
$service->validateDependencies('Blog');

// Get dependency tree
$tree = $service->getDependencyTree('Blog');

// Check if can disable
$canDisable = $service->canDisable('User'); // false if Blog depends on it

// Get modules that depend on this one
$dependents = $service->getDependents('User'); // ['Blog', 'Shop']
```

---

### 2. 🔄 Module Update System

Check and apply updates from GitHub releases with automatic backups.

#### Configuration

```php
'updates' => [
    'enabled' => true,
    'auto_check' => false,
    'check_frequency' => 24, // hours
],
```

#### Setup Module for Updates

Add repository to `module.json`:

```json
{
  "name": "Blog",
  "version": "1.0.0",
  "repository": "username/blog-module"
}
```

#### Features

- ✅ **Version Comparison** - Automatic detection of newer versions
- ✅ **Changelog Display** - Shows release notes before updating
- ✅ **Automatic Backup** - Creates backup before applying update
- ✅ **Tag/Release Support** - Install from specific versions
- ✅ **Batch Updates** - Check all modules at once

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\ModuleUpdateService;

$service = app(ModuleUpdateService::class);

// Check for update
$updateData = $service->checkForUpdate('Blog');

if ($updateData->updateAvailable) {
    echo "Update available: {$updateData->latestVersion}";
    echo "Changelog: {$updateData->changelog}";

    // Apply update
    $service->updateModule('Blog');
}

// Batch check all modules
$updates = $service->batchCheckUpdates();
```

---

### 3. 💾 Backup & Restore

Automatic backups before critical operations with one-click restore.

#### Configuration

```php
'backups' => [
    'enabled' => true,
    'backup_before_update' => true,
    'backup_before_uninstall' => true,
    'retention_days' => 30,
],
```

#### Features

- ✅ **Automatic Backups** - Before updates and uninstalls
- ✅ **ZIP Compression** - Efficient storage
- ✅ **Metadata Tracking** - Size, date, reason, user
- ✅ **One-Click Restore** - Restore from backup instantly
- ✅ **Retention Management** - Auto-cleanup old backups

#### Storage

- **Backups:** `storage/app/module-backups/*.zip`
- **Metadata:** `storage/app/module-backups/backups.json`

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\ModuleBackupService;

$service = app(ModuleBackupService::class);

// Create backup
$backup = $service->createBackup('Blog', 'Manual backup');

// List backups
$backups = $service->getBackups('Blog');

// Restore from backup
$service->restoreBackup($backup->id);

// Delete old backups
$service->deleteBackup($backup->id);
```

---

### 4. 🏥 Health Monitoring

Automated health checks with scoring and status categorization.

#### Configuration

```php
'health_checks' => [
    'enabled' => true,
    'auto_check' => true, // After install/update
],
```

#### Health Checks Performed

| Check                | Description                         |
| -------------------- | ----------------------------------- |
| **Module Exists**    | Module directory is present         |
| **module.json**      | Configuration file exists and valid |
| **composer.json**    | Composer file exists (if used)      |
| **Service Provider** | Provider class exists               |
| **Dependencies**     | All dependencies are met            |
| **Files Intact**     | Core files are present              |

#### Health Scoring

- **🟢 Healthy (80-100)** - All checks passed
- **🟡 Warning (50-79)** - Some checks failed
- **🔴 Critical (0-49)** - Multiple failures

#### Storage

- **Health Data:** `storage/app/module-manager/health-checks.json`

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\ModuleHealthService;

$service = app(ModuleHealthService::class);

// Check module health
$health = $service->checkHealth('Blog');

echo "Status: {$health->status}"; // healthy, warning, critical
echo "Score: {$health->score}/100";
echo "Message: {$health->message}";

// Individual checks
foreach ($health->checks as $check => $passed) {
    echo "{$check}: " . ($passed ? '✅' : '❌');
}
```

---

### 5. 📝 Audit Logging

Complete audit trail of all module operations for compliance and debugging.

#### Logged Information

- **Action** - install, uninstall, enable, disable, update, backup, restore
- **Module Name** - Which module was affected
- **User** - ID and name of user who performed action
- **IP Address** - Request IP
- **User Agent** - Browser/client information
- **Timestamp** - When action occurred
- **Status** - Success or failure
- **Error Message** - If action failed
- **Metadata** - Additional context

#### Storage

- **Audit Logs:** `storage/app/module-manager/audit-logs.json`
- **Retention:** Last 1000 entries

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\AuditLogService;

$service = app(AuditLogService::class);

// Log an action
$service->log(
    action: 'install',
    moduleName: 'Blog',
    success: true,
    metadata: ['version' => '1.0.0']
);

// Logs are automatically created for:
// - Module install/uninstall
// - Module enable/disable
// - Module updates
// - Backup creation/restoration
```

---

### 6. 🐙 Enhanced GitHub Integration

Advanced GitHub API integration with release management and OAuth support.

#### Configuration

```php
'github' => [
    'token' => env('GITHUB_TOKEN'), // Optional, increases rate limits
    'default_branch' => 'main',
    'fallback_branch' => 'master',
],
```

#### Features

- ✅ **Release Management** - Fetch and install from releases
- ✅ **Tag Support** - Install specific versions
- ✅ **Changelog Retrieval** - Display release notes
- ✅ **OAuth Token** - Support for private repositories
- ✅ **Rate Limit Management** - Handles API limits gracefully
- ✅ **Branch Fallback** - Tries main, falls back to master

#### Setup

1. **Add GitHub Token (Optional)**

   ```bash
   # .env
   GITHUB_TOKEN=ghp_your_token_here
   ```

2. **Add Repository to module.json**
   ```json
   {
     "name": "Blog",
     "version": "1.0.0",
     "repository": "username/blog-module"
   }
   ```

#### Usage Example

```php
use Alizharb\FilamentModuleManager\Services\GitHubService;

$service = app(GitHubService::class);

// Get latest release
$release = $service->getLatestRelease('username/blog-module');

// Get all releases
$releases = $service->getAllReleases('username/blog-module');

// Get specific release
$release = $service->getReleaseByTag('username/blog-module', 'v1.0.0');

// Download release
$zipPath = $service->downloadRelease('username/blog-module', 'v1.0.0');

// Get changelog
$changelog = $service->getChangelog('username/blog-module', 'v1.0.0');
```

---

## ⚙️ Configuration

### Navigation Settings

```php
'navigation' => [
    'register' => true,
    'sort' => 100,
    'icon' => 'heroicon-o-code-bracket',
    'group' => 'System',
    'label' => 'Module Manager',
],
```

### Upload Settings

```php
'upload' => [
    'disk' => 'public',
    'temp_directory' => 'temp/modules',
    'max_size' => 20 * 1024 * 1024, // 20MB
],
```

### Widget Settings

```php
'widget' => [
    'enabled' => true,
    'show_on_dashboard' => true,
    'show_on_module_page' => true,
],
```

### Permissions

```php
'permissions' => [
    'enabled' => true,
    'prefix' => 'module',
    'actions' => [
        'view' => 'module.view',
        'install' => 'module.install',
        'uninstall' => 'module.uninstall',
        'enable' => 'module.enable',
        'disable' => 'module.disable',
        'update' => 'module.update',
    ],
],
```

---

## 💡 Usage Examples

### Programmatic Module Management

```php
use Alizharb\FilamentModuleManager\Facades\ModuleManager;

// Enable a module
ModuleManager::enable('Blog');

// Disable a module
ModuleManager::disable('Blog');

// Get module data
$module = ModuleManager::findModule('Blog');

// Install from ZIP
$result = ModuleManager::installModulesFromZip('/path/to/module.zip');

// Install from GitHub
$result = ModuleManager::installModuleFromGitHub('Blog');

// Uninstall module
$result = ModuleManager::uninstallModule('Blog');
```

### Working with Dependencies

```php
use Alizharb\FilamentModuleManager\Services\ModuleDependencyService;

$service = app(ModuleDependencyService::class);

// Get all dependencies
$dependencies = $service->getModuleDependencies('Blog');

// Get dependency tree
$tree = $service->getDependencyTree('Blog');

// Resolve installation order
$order = $service->resolveDependencies(['Blog', 'Shop', 'User']);
```

### Health Monitoring

```php
use Alizharb\FilamentModuleManager\Services\ModuleHealthService;

$service = app(ModuleHealthService::class);

$health = $service->checkHealth('Blog');

if ($health->isCritical()) {
    // Handle critical issues
    Log::error("Module Blog has critical issues: {$health->message}");
}
```

---

## 🌍 Multi-Language Support

The package includes translations for 20+ languages:

- English, Arabic, Spanish, French, German
- Italian, Portuguese, Russian, Chinese, Japanese
- And more...

### Customize Translations

```bash
php artisan vendor:publish --tag="filament-module-manager-translations"
```

Edit files in `lang/vendor/filament-module-manager/`.

---

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

### Development Setup

```bash
# Clone repository
git clone https://github.com/AlizHarb/filament-module-manager.git

# Install dependencies
composer install

# Run tests
composer test

# Format code
composer format
```

### Testing

```bash
# Run all tests
composer test

# Run specific test
./vendor/bin/pest --filter=ModuleManagerTest
```

---

## 💖 Sponsor This Project

If this package helps you, consider sponsoring its development:

<div align="center">

[![Sponsor on GitHub](https://img.shields.io/badge/Sponsor-GitHub-red?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/AlizHarb)

</div>

Your support helps maintain and improve this package! 🙏

---

## 🐛 Issues & Support

- 🐛 **Bug Reports**: [Create an issue](https://github.com/AlizHarb/filament-module-manager/issues/new?template=bug_report.md)
- 💡 **Feature Requests**: [Request a feature](https://github.com/AlizHarb/filament-module-manager/issues/new?template=feature_request.md)
- 💬 **Discussions**: [Join the discussion](https://github.com/AlizHarb/filament-module-manager/discussions)

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- [Filament PHP](https://filamentphp.com/) - Amazing admin panel framework
- [Nwidart Laravel Modules](https://nwidart.com/laravel-modules) - Solid module foundation
- [Spatie](https://spatie.be/) - Excellent Laravel packages
- All contributors and supporters 🎉

---

<div align="center">
    <p>
        <strong>Made with ❤️ by <a href="https://github.com/AlizHarb">Ali Harb</a></strong><br>
        <sub>Star ⭐ this repository if it helped you!</sub>
    </p>
</div>
