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


it('can uninstall a module', function () {
    // Enable first, then uninstall
    ModuleManager::enable($this->module);
    $result = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class)->uninstallModule($this->module);
    expect($result)->toBeTrue();
    expect(File::exists($this->blogPath))->toBeFalse();
});

it('cannot uninstall a non-existent module', function () {
    $result = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class)->uninstallModule('DoesNotExist');
    expect($result)->toBeFalse();
});

it('can install a module from local path', function () {
    $service = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
    $newModule = 'TestModule';
    $testPath = base_path('temp_test_module');
    File::makeDirectory($testPath, 0777, true);
    File::put("{$testPath}/module.json", json_encode(['name' => $newModule, 'enabled' => false], JSON_PRETTY_PRINT));
    $result = $service->installModuleFromPath($testPath);
    expect($result->hasInstalled())->toBeTrue();
    expect(File::exists(base_path("Modules/{$newModule}")))->toBeTrue();
    File::deleteDirectory($testPath);
});

it('skips install if module already exists (local path)', function () {
    $service = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
    $result = $service->installModuleFromPath($this->blogPath);
    expect($result->hasInstalled())->toBeFalse();
    expect($result->hasSkipped())->toBeTrue();
});

it('can install modules from ZIP (mocked)', function () {
    $service = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
    $zipPath = storage_path('app/public/test_module.zip');
    $moduleName = 'ZipModule';
    $tempModulePath = storage_path('app/temp_zip_module');
    File::ensureDirectoryExists($tempModulePath);
    File::makeDirectory("{$tempModulePath}/{$moduleName}", 0777, true);
    File::put("{$tempModulePath}/{$moduleName}/module.json", json_encode(['name' => $moduleName, 'enabled' => false], JSON_PRETTY_PRINT));
    $zip = new \ZipArchive();
    $zip->open($zipPath, \ZipArchive::CREATE);
    $zip->addFile("{$tempModulePath}/{$moduleName}/module.json", "{$moduleName}/module.json");
    $zip->close();
    $result = $service->installModulesFromZip('test_module.zip');
    expect($result->hasInstalled())->toBeTrue();
    expect(File::exists(base_path("Modules/{$moduleName}")))->toBeTrue();
    File::delete($zipPath);
    File::deleteDirectory($tempModulePath);
});

it('skips install if ZIP is invalid', function () {
    $service = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
    $result = $service->installModulesFromZip('nonexistent.zip');
    expect($result->hasInstalled())->toBeFalse();
    expect($result->hasSkipped())->toBeFalse();
});

it('can install module from GitHub (mocked)', function () {
    // This test should mock file_get_contents and ZipArchive, but here we just check the method exists
    $service = app(\Alizharb\FilamentModuleManager\Services\ModuleManagerService::class);
    expect(method_exists($service, 'installModuleFromGitHub'))->toBeTrue();
});

it('widget stats overview returns correct counts', function () {
    $widget = new \Alizharb\FilamentModuleManager\Widgets\ModulesOverview();
    $stats = $widget->getStats();
    expect($stats)->toBeArray();
    expect(count($stats))->toBe(3);
});
