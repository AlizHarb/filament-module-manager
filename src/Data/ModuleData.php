<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Data;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Transformation\TransformationContextFactory;
use Spatie\LaravelData\Support\Validation\ValidationContext;

/**
 * Data Transfer Object for Module entities
 *
 * Represents module information with validation rules and transformation capabilities
 */
final class ModuleData extends Data
{
    /**
     * Constructs a new ModuleData instance
     *
     * @param  string  $name  The unique name of the module
     * @param  string  $alias  The alias/short identifier for the module
     * @param  string|null  $description  Optional description of the module's purpose
     * @param  bool  $active  Indicates if the module is currently active
     * @param  string  $path  Filesystem path to the module
     * @param  string|null  $version  Current version of the module (semantic versioning)
     * @param  null|array|string  $authors  Optional array of authors or string representing the author's name'
     */
    public function __construct(
        #[Required, StringType]
        public string $name,

        #[Required, StringType]
        public string $alias,

        #[Nullable, StringType, Sometimes]
        public ?string $description,

        #[BooleanType]
        public bool $active,

        #[Required, StringType]
        public string $path,

        #[Nullable, StringType, Sometimes]
        public ?string $version,

        #[Nullable, Sometimes]
        public null|array|string $authors = null,
    ) {}

    /**
     * Provides validation rules for the ModuleData object
     *
     * @return array<string, array<int, mixed>>
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'alias' => ['required', 'string', 'max:100', 'alpha_dash'],
            'description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'active' => ['required', 'boolean'],
            'path' => ['required', 'string', 'max:512'],
            'version' => ['sometimes', 'nullable', 'string', 'regex:/^\d+\.\d+\.\d+$/'],
            'authors' => ['sometimes', 'nullable', 'array'],
        ];
    }

    /**
     * Provides custom validation messages
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'name.required' => __('filament-module-manager::filament-module.validation.name_required'),
            'alias.required' => __('filament-module-manager::filament-module.validation.alias_required'),
            'alias.alpha_dash' => __('filament-module-manager::filament-module.validation.alias_alpha_dash'),
            'path.required' => __('filament-module-manager::filament-module.validation.path_required'),
            'version.regex' => __('filament-module-manager::filament-module.validation.version_regex'),
            'authors.array' => __('filament-module-manager::filament-module.validation.authors_array'),
        ];
    }

    /**
     * Default values for optional properties
     *
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'description' => null,
            'version' => null,
            'active' => false,
            'authors' => null,
        ];
    }

    /**
     * Cast properties to appropriate types
     *
     * @return array<string, string>
     */
    public static function casts(): array
    {
        return [
            'active' => 'boolean',
            'authors' => 'array',
        ];
    }

    /**
     * Custom transformation when creating from array
     *
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            name: (string) ($payload['name'] ?? ''),
            alias: (string) ($payload['alias'] ?? ''),
            description: isset($payload['description']) ? (string) $payload['description'] : null,
            active: (bool) ($payload['active'] ?? false),
            path: (string) ($payload['path'] ?? ''),
            version: isset($payload['version']) ? (string) $payload['version'] : null,
            authors: isset($payload['authors']) ? $payload['authors'] : null,
        );
    }

    /**
     * Prepare data for transformation to array
     *
     * @return array<string, mixed>
     */
    public function transform(null|TransformationContextFactory|TransformationContext $transformationContext = null): array
    {
        return [
            'name' => $this->name,
            'alias' => $this->alias,
            'description' => $this->description,
            'active' => $this->active,
            'path' => $this->path,
            'version' => $this->version,
            'authors' => $this->authors,
        ];
    }
}
