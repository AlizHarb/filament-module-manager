<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Alizharb\FilamentModuleManager\Data\ModuleUpdateData;
use Alizharb\FilamentModuleManager\Exceptions\UpdateException;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as ModuleFacade;

/**
 * Service for module update management
 *
 * Handles checking for updates from GitHub releases, comparing versions,
 * and applying updates with automatic backups. Integrates with GitHubService
 * for release information and ModuleBackupService for safety.
 */
class ModuleUpdateService
{
    /**
     * Create a new module update service instance
     *
     * @param  GitHubService  $githubService  Service for GitHub API interactions
     * @param  ModuleBackupService  $backupService  Service for creating backups
     */
    public function __construct(
        private GitHubService $githubService,
        private ModuleBackupService $backupService
    ) {}

    /**
     * Check if an update is available for a module
     *
     * Compares the installed version with the latest GitHub release.
     *
     * @param  string  $moduleName  The module to check for updates
     * @return ModuleUpdateData Update information including availability and changelog
     */
    public function checkForUpdate(string $moduleName): ?ModuleUpdateData
    {
        $module = ModuleFacade::find($moduleName);

        if (! $module) {
            return null;
        }

        $currentVersion = $module->get('version');
        $repository = $module->get('repository');

        if (! $repository) {
            return null;
        }

        $repo = $this->githubService->parseRepository($repository);
        $latestRelease = $this->githubService->getLatestRelease($repo);

        if (! $latestRelease) {
            return null;
        }

        $latestVersion = ltrim($latestRelease['tag_name'], 'v');
        $updateAvailable = version_compare($latestVersion, $currentVersion, '>');

        return new ModuleUpdateData(
            moduleName: $moduleName,
            currentVersion: $currentVersion,
            latestVersion: $latestVersion,
            updateAvailable: $updateAvailable,
            changelog: $latestRelease['body'] ?? null,
            downloadUrl: $latestRelease['zipball_url'] ?? null,
            releaseDate: $latestRelease['published_at'] ?? null
        );
    }

    /**
     * Update a module to a specific version
     */
    public function updateModule(string $moduleName, ?string $version = null): bool
    {
        $module = ModuleFacade::find($moduleName);

        if (! $module) {
            throw UpdateException::updateFailed($moduleName, 'Module not found');
        }

        $repository = $module->get('repository');

        if (! $repository) {
            throw UpdateException::updateFailed($moduleName, 'No repository configured');
        }

        try {
            // Create backup before update
            $this->backupService->createBackup($moduleName, 'Before update');

            $repo = $this->githubService->parseRepository($repository);

            // Get version to install
            if (! $version) {
                $latestRelease = $this->githubService->getLatestRelease($repo);
                $version = ltrim($latestRelease['tag_name'] ?? '', 'v');
            }

            if (! $version) {
                throw UpdateException::noUpdateAvailable($moduleName);
            }

            // Download and install
            $tag = 'v'.$version;
            $zipPath = $this->githubService->downloadRelease($repo, $tag);

            if (! $zipPath) {
                throw UpdateException::updateFailed($moduleName, 'Failed to download update');
            }

            // Install the update
            $moduleManagerService = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
            $result = $moduleManagerService->installModulesFromZip($zipPath, true);

            // Cleanup
            if (File::exists($zipPath)) {
                File::delete($zipPath);
            }

            return ! empty($result->installed);
        } catch (\Throwable $e) {
            throw UpdateException::updateFailed($moduleName, $e->getMessage());
        }
    }

    /**
     * Check for updates for all modules
     */
    public function batchCheckUpdates(): array
    {
        $updates = [];

        foreach (ModuleFacade::all() as $module) {
            $updateData = $this->checkForUpdate($module->getName());

            if ($updateData && $updateData->updateAvailable) {
                $updates[] = $updateData;
            }
        }

        return $updates;
    }

    /**
     * Get changelog for a specific version
     */
    public function getChangelog(string $moduleName, string $version): ?string
    {
        $module = ModuleFacade::find($moduleName);

        if (! $module) {
            return null;
        }

        $repository = $module->get('repository');

        if (! $repository) {
            return null;
        }

        $repo = $this->githubService->parseRepository($repository);
        $tag = 'v'.$version;

        return $this->githubService->getChangelog($repo, $tag);
    }
}
