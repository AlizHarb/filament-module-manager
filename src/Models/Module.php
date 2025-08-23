<?php

namespace Alizharb\FilamentModuleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Nwidart\Modules\Module as NwidartModule;
use Sushi\Sushi;
use Alizharb\FilamentModuleManager\Data\ModuleData;

/**
 * Module model representing application modules with Sushi-powered in-memory storage.
 *
 * @property string $name
 * @property string $alias
 * @property string|null $description
 * @property bool $active
 * @property string $path
 * @property string|null $version
 *
 * @method static Builder active() Scope for active modules
 * @method static Builder inactive() Scope for inactive modules
 * @method static Builder byName(string $name) Scope to find by name
 * @method static Builder byVersion(string $version) Scope to find by version
 * @method static Builder withKeyword(string $keyword) Scope to find by keyword
 */
class Module extends Model
{
    use Sushi;

    /** @var string[] */
    protected $guarded = [];

    /** @var bool */
    public $incrementing = false;

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = 'name';

    /** @var string */
    protected $keyType = 'string';

    /** @var array<string, string> */
    protected $schema = [
        'name' => 'string',
        'alias' => 'string',
        'description' => 'text',
        'active' => 'boolean',
        'path' => 'string',
        'version' => 'string',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'active' => 'boolean',
        'authors' => 'array',
    ];

    /**
     * Get the rows for the Sushi-powered database with comprehensive module data.
     *
     * @return array<array{
     *     name: string,
     *     alias: string,
     *     description: string|null,
     *     active: bool,
     *     path: string,
     *     version: string|null,
     * }>
     */
    public function getRows(): array
    {
        return ModuleFacade::toCollection()
            ->map(fn (NwidartModule $module): array => [
                'name' => $module->getName(),
                'alias' => $module->getLowerName(),
                'description' => $module->get('description'),
                'active' => $module->isEnabled(),
                'path' => $module->getPath(),
                'version' => $module->getComposerAttr('version'),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Scope a query to only include active modules.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include inactive modules.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Scope a query to find modules by name.
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * Scope a query to find modules by version.
     */
    public function scopeByVersion(Builder $query, string $version): Builder
    {
        return $query->where('version', $version);
    }

    /**
     * Get the cache key for Sushi's cached schema.
     */
    protected function sushiCacheReferencePath(): string
    {
        return config_path('modules.php');
    }

    /**
     * Determine if Sushi should cache the schema.
     */
    public function sushiShouldCache(): bool
    {
        return app()->isProduction();
    }

    /**
     * Get the cache duration for Sushi's cached schema.
     */
    public function sushiCacheDuration(): int
    {
        return app()->isProduction() ? 3600 : 60;
    }

    /**
     * Get all modules as typed ModuleData objects.
     *
     * @return Collection<ModuleData>
     */
    public static function allData(): Collection
    {
        return self::all()
            ->map(fn (self $module): ModuleData => new ModuleData(
                name: $module->name,
                alias: $module->alias,
                description: $module->description,
                active: $module->active,
                path: $module->path,
                version: $module->version,
            ));
    }

    /**
     * Find a module by name and return a ModuleData object.
     */
    public static function findData(string $name): ?ModuleData
    {
        $module = self::find($name);

        if (!$module instanceof self) {
            return null;
        }

        return new ModuleData(
            name: $module->name,
            alias: $module->alias,
            description: $module->description,
            active: $module->active,
            path: $module->path,
            version: $module->version,
        );
    }

    /**
     * Get active modules count.
     */
    public static function getActiveCount(): int
    {
        return self::active()->count();
    }

    /**
     * Get inactive modules count.
     */
    public static function getInactiveCount(): int
    {
        return self::inactive()->count();
    }

    /**
     * Get modules grouped by license.
     *
     * @return Collection<string, Collection<ModuleData>>
     */
    public static function groupedByLicense(): Collection
    {
        return self::allData()->groupBy('license');
    }

    /**
     * Check if a module exists by name.
     */
    public static function existsByName(string $name): bool
    {
        return self::where('name', $name)->exists();
    }

    /**
     * Convert the model instance to ModuleData.
     */
    public function toData(): ModuleData
    {
        return new ModuleData(
            name: $this->name,
            alias: $this->alias,
            description: $this->description,
            active: $this->active,
            path: $this->path,
            version: $this->version,
        );
    }

    /**
     * Get the module's composer attributes as a collection.
     */
    public function getComposerAttributes(): Collection
    {
        return collect([
            'version' => $this->version,
            'license' => $this->license,
            'keywords' => $this->keywords,
            'authors' => $this->authors,
            'homepage' => $this->homepage,
            'minimum_stability' => $this->minimum_stability,
            'prefer_stable' => $this->prefer_stable,
        ])->filter();
    }

    /**
     * Check if the module has authors.
     */
    public function hasAuthors(): bool
    {
        return !empty($this->authors);
    }

    /**
     * Get the first author's name if available.
     */
    public function getFirstAuthorName(): ?string
    {
        return $this->authors[0]['name'] ?? null;
    }
}
