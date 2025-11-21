<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for GitHub API integration
 *
 * Handles all interactions with the GitHub API including fetching releases,
 * downloading archives, and retrieving repository information.
 */
class GitHubService
{
    /**
     * GitHub API base URL
     */
    private string $apiBase = 'https://api.github.com';

    /**
     * Get the latest release for a repository
     *
     * @param  string  $repo  Repository in format 'owner/repo'
     * @return array<string, mixed>|null Release data or null if not found
     */
    public function getLatestRelease(string $repo): ?array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBase}/repos/{$repo}/releases/latest");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Failed to fetch latest release for {$repo}: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Get all releases for a repository
     */
    public function getReleases(string $repo, int $perPage = 10): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBase}/repos/{$repo}/releases", [
                    'per_page' => $perPage,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Throwable $e) {
            Log::error("Failed to fetch releases for {$repo}: {$e->getMessage()}");

            return [];
        }
    }

    /**
     * Get a specific release by tag
     */
    public function getReleaseByTag(string $repo, string $tag): ?array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBase}/repos/{$repo}/releases/tags/{$tag}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Failed to fetch release {$tag} for {$repo}: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Download a release archive
     */
    public function downloadRelease(string $repo, string $tag): ?string
    {
        try {
            $url = "https://github.com/{$repo}/archive/refs/tags/{$tag}.zip";

            $tempPath = storage_path('app/temp/modules');
            if (! file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            $fileName = str_replace('/', '_', $repo)."_{$tag}.zip";
            $filePath = "{$tempPath}/{$fileName}";

            $response = Http::withHeaders($this->getHeaders())
                ->sink($filePath)
                ->get($url);

            if ($response->successful()) {
                return $filePath;
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Failed to download release {$tag} for {$repo}: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Get repository information
     */
    public function getRepository(string $repo): ?array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiBase}/repos/{$repo}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error("Failed to fetch repository info for {$repo}: {$e->getMessage()}");

            return null;
        }
    }

    /**
     * Get changelog/release notes
     */
    public function getChangelog(string $repo, string $tag): ?string
    {
        $release = $this->getReleaseByTag($repo, $tag);

        return $release['body'] ?? null;
    }

    /**
     * Get headers for GitHub API requests
     */
    private function getHeaders(): array
    {
        $headers = [
            'Accept' => 'application/vnd.github.v3+json',
        ];

        $token = config('filament-module-manager.github.token');

        if ($token) {
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    /**
     * Parse repository from various formats
     */
    public function parseRepository(string $input): string
    {
        // Already in owner/repo format
        if (preg_match('/^[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+$/', $input)) {
            return $input;
        }

        // Full GitHub URL
        if (preg_match('/github\.com\/([a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        return $input;
    }
}
