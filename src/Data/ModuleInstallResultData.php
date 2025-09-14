<?php

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * Data Transfer Object for Module Installation Results
 *
 * Represents the outcome of a module installation operation including
 * successfully installed modules and those that were skipped
 */
final class ModuleInstallResultData extends Data
{
    /**
     * Constructs a new ModuleInstallResultData instance
     *
     * @param  DataCollection<int, ModuleData>|array<ModuleData>  $installed  Collection of successfully installed modules
     * @param  array<string>  $skipped  Array of module names that were skipped during installation
     */
    public function __construct(
        #[ArrayType, Sometimes]
        #[DataCollectionOf(ModuleData::class)]
        public DataCollection|array $installed,

        #[ArrayType, Sometimes]
        public array $skipped = [],
    ) {}

    /**
     * Default values for properties
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'installed' => [],
            'skipped' => [],
        ];
    }

    /**
     * Prepare the data for transformation
     * Ensures arrays are converted to DataCollections when needed
     */
    protected function prepareForTransformation(): void
    {
        if (is_array($this->installed) && ! $this->installed instanceof DataCollection) {
            $this->installed = new DataCollection(ModuleData::class, $this->installed);
        }
    }

    /**
     * Provides validation rules for the ModuleInstallResultData object
     *
     * @return array<string, array<int, mixed>>
     */
    public static function rules(): array
    {
        return [
            'installed' => ['sometimes', 'array'],
            'installed.*' => ['sometimes', 'instance_of:'.ModuleData::class],
            'skipped' => ['sometimes', 'array'],
            'skipped.*' => ['sometimes', 'string'],
        ];
    }

    /**
     * Get the total count of processed modules (installed + skipped)
     */
    public function getTotalProcessed(): int
    {
        $installedCount = is_array($this->installed) ? count($this->installed) : $this->installed->count();

        return $installedCount + count($this->skipped);
    }

    /**
     * Check if any modules were installed
     */
    public function hasInstalled(): bool
    {
        $installedCount = is_array($this->installed) ? count($this->installed) : $this->installed->count();

        return $installedCount > 0;
    }

    /**
     * Check if any modules were skipped
     */
    public function hasSkipped(): bool
    {
        return count($this->skipped) > 0;
    }

    /**
     * Get the names of all installed modules
     *
     * @return array<string>
     */
    public function getInstalledNames(): array
    {
        if (is_array($this->installed)) {
            return array_map(fn (ModuleData $module) => $module->name, $this->installed);
        }

        return $this->installed->map(fn (ModuleData $module) => $module->name)->toArray();
    }

    /**
     * Create a successful installation result
     *
     * @param  array<ModuleData>  $installed
     */
    public static function success(array $installed): self
    {
        return new self(
            installed: $installed,
            skipped: []
        );
    }

    /**
     * Create a result with skipped modules
     *
     * @param  array<string>  $skipped
     */
    public static function skipped(array $skipped): self
    {
        return new self(
            installed: [],
            skipped: $skipped
        );
    }

    /**
     * Create a mixed result with both installed and skipped modules
     *
     * @param  array<ModuleData>  $installed
     * @param  array<string>  $skipped
     */
    public static function mixed(array $installed, array $skipped): self
    {
        return new self(
            installed: $installed,
            skipped: $skipped
        );
    }

    /**
     * Convert the installed collection to a DataCollection if it's an array
     * This ensures proper transformation and serialization
     *
     * @return DataCollection<int, ModuleData>
     */
    public function getInstalledCollection(): DataCollection
    {
        if ($this->installed instanceof DataCollection) {
            return $this->installed;
        }

        return new DataCollection(ModuleData::class, $this->installed);
    }
}
