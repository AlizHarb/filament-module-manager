<?php

namespace Alizharb\FilamentModuleManager\Tests;

use Alizharb\FilamentModuleManager\FilamentModuleManagerServiceProvider;
use Filament\FilamentServiceProvider;
use Livewire\LivewireServiceProvider;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelData\LaravelDataServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelModulesServiceProvider::class,
            FilamentServiceProvider::class,
            LivewireServiceProvider::class,
            LaravelDataServiceProvider::class,
            FilamentModuleManagerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
