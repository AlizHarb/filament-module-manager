<?php

declare(strict_types=1);

namespace Alizharb\FilamentModuleManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Sushi\Sushi;

class ModuleDependency extends Model
{
    use Sushi;

    public $incrementing = false;

    public $timestamps = false;

    protected $schema = [
        'module_name' => 'string',
        'depends_on' => 'string',
        'version_constraint' => 'string',
        'required' => 'boolean',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    /**
     * Get rows from all module.json files
     */
    public function getRows(): array
    {
        $dependencies = [];

        foreach (ModuleFacade::all() as $module) {
            $moduleJsonPath = $module->getPath().'/module.json';

            if (! File::exists($moduleJsonPath)) {
                continue;
            }

            try {
                $config = json_decode(File::get($moduleJsonPath), true, 512, JSON_THROW_ON_ERROR);
                $requires = $config['requires'] ?? [];

                foreach ($requires as $dependency => $constraint) {
                    $dependencies[] = [
                        'module_name' => $module->getName(),
                        'depends_on' => is_numeric($dependency) ? $constraint : $dependency,
                        'version_constraint' => is_numeric($dependency) ? null : $constraint,
                        'required' => true,
                    ];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $dependencies;
    }

    protected function sushiShouldCache(): bool
    {
        return app()->isProduction();
    }
}
