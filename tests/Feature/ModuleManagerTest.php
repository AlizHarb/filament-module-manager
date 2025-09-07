<?php

use Alizharb\FilamentModuleManager\Facades\ModuleManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\artisan;

beforeEach(function () {
    // Set up a fake modules directory
    $this->module = 'Blog';
    $this->modulesPath = base_path('Modules');
    $this->blogPath = "{$this->modulesPath}/{$this->module}";
    $this->moduleJsonPath = "{$this->blogPath}/module.json";

    File::deleteDirectory($this->modulesPath); // Clean up before
    File::makeDirectory($this->blogPath, 0777, true);
    File::put($this->moduleJsonPath, json_encode([
        'name' => $this->module,
        'enabled' => false,
    ], JSON_PRETTY_PRINT));
});

afterEach(function () {
    File::deleteDirectory($this->modulesPath);
});

it('can enable a disabled module', function () {
    expect(File::get($this->moduleJsonPath))->toContain('"enabled": false');

    ModuleManager::enable($this->module);

    $updated = json_decode(File::get($this->moduleJsonPath), true);

    expect($updated['enabled'])->toBeTrue();
});

it('can disable an enabled module', function () {
    // Simulate enabled
    File::put($this->moduleJsonPath, json_encode([
        'name' => $this->module,
        'enabled' => true,
    ], JSON_PRETTY_PRINT));

    expect(File::get($this->moduleJsonPath))->toContain('"enabled": true');

    ModuleManager::disable($this->module);

    $updated = json_decode(File::get($this->moduleJsonPath), true);

    expect($updated['enabled'])->toBeFalse();
});

it('throws exception if module does not exist', function () {
    $badModule = 'DoesNotExist';

    $this->expectException(\Alizharb\FilamentModuleManager\Exceptions\ModuleNotFoundException::class);

    ModuleManager::enable($badModule);
});

it('updates database or module.json on enable/disable', function () {
    // Here, test both enable and disable change the .json file

    ModuleManager::enable($this->module);
    $updated = json_decode(File::get($this->moduleJsonPath), true);
    expect($updated['enabled'])->toBeTrue();

    ModuleManager::disable($this->module);
    $updated = json_decode(File::get($this->moduleJsonPath), true);
    expect($updated['enabled'])->toBeFalse();
});
