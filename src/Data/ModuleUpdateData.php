<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Data;

/**
 * Data Transfer Object for Module Update Information
 *
 * Contains information about available updates for a module, including
 * version details, changelog, and download information from GitHub releases.
 */
final class ModuleUpdateData extends Data
{
    /**
     * Create a new module update data instance.
     *
     * @param  string  $moduleName  The name of the module
     * @param  string  $currentVersion  The currently installed version
     * @param  string  $latestVersion  The latest available version from GitHub
     * @param  bool  $updateAvailable  Whether an update is available (latestVersion > currentVersion)
     * @param  string|null  $changelog  Release notes/changelog from the GitHub release
     * @param  string|null  $downloadUrl  Direct download URL for the release archive
     * @param  string|null  $releaseDate  ISO 8601 timestamp of when the release was published
     */
    public function __construct(
        public string $moduleName,
        public string $currentVersion,
        public string $latestVersion,
        public bool $updateAvailable,
        public ?string $changelog = null,
        public ?string $downloadUrl = null,
        public ?string $releaseDate = null,
    ) {}
}
