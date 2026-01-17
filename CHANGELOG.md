# Changelog

All notable changes to `filament-module-manager` will be documented in this file.

## v2.2.0 - 2026-01-16

### Added

- **Filament v5 Support**
  - Added support for Filament v5 (in addition to v4)
  - Updated dependencies to allow `filament/filament: ^4.0|^5.0`

- **PHP 8.3+ Support**
  - Added full support for PHP 8.3 and 8.4
  - Updated CI/CD validation matrix

### Fixed

- **CI/CD Improvements**
  - Fixed test runner configuration for newer PHP versions
  - Broadened test dependency constraints for better compatibility

## v2.1.0 - 2025-12-10

### Added

- **Enhanced Configuration System**
  - Added comprehensive configuration file with all options
  - Configurable storage paths for backups, health checks, and audit logs
  - Added `max_backups_per_module` setting
  - Added `cache_duration` for health checks
  - Added audit log settings (max_logs, retention_days)
  - Permissions can now be disabled via config

- **Improved Backup System**
  - Backward-compatible path resolution for existing backups
  - Support for both absolute and relative backup paths
  - Better error messages showing actual file paths
  - Configurable backup retention and limits

- **Enhanced Testing**
  - Added cleanup for module-backups directory in tests
  - Fixed test property declarations
  - All 22 tests passing with 33 assertions
  - Added GitHub Actions CI/CD workflow

- **Localization Improvements**
  - Completed all 25+ language translations
  - Fixed Traditional Chinese (zh-TW) localization structure
  - Properly nested translation arrays for all languages

### Fixed

- Fixed backup restoration issues with path resolution
- Fixed health check data structure for array access
- Fixed update service nullable return types
- Fixed notification action imports for Filament v4
- Fixed ModuleBackupData to accept nullable version

### Changed

- Updated config file structure to match repository standards
- Improved documentation in CHANGELOG and README
- Enhanced error handling in backup service

### Technical

- Maintained PHP 8.2+ compatibility
- Maintained Filament v4 compatibility
- Maintained Laravel 11+ support
- PSR-12 code style compliance

---

## v2.0.0 - Previous Release

### Enterprise Features

- Dependency Management with circular detection
- Update System with GitHub integration
- Backup & Restore functionality
- Health Monitoring with scoring
- Audit Logging system
- GitHub Integration with OAuth support

---

## v1.0.0 - Initial Release

### Added

- Core module management (enable, disable, install, uninstall)
- Multi-source installation (ZIP, GitHub, local path)
- Module overview widget
- Basic health checks
- Module policy for authorization
- Multi-language support
