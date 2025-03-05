<?php

namespace AsevenTeam\LaravelAccounting\Tests;

use AsevenTeam\LaravelAccounting\LaravelAccountingServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'AsevenTeam\\LaravelAccounting\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAccountingServiceProvider::class,
        ];
    }

    public function defineEnvironment($app)
    {
        config()->set('database.default', 'testing');

        config()->set('data', require __DIR__.'/../vendor/spatie/laravel-data/config/data.php');

        foreach ($this->migrationFiles() as $file) {
            $migration = include __DIR__.'/../database/migrations/'.$file;
            $migration->up();
        }
    }

    protected function migrationFiles(): array
    {
        return [
            'create_accounting_tables.php',
            'create_ledgers_table.php',
            'create_starting_balances_table.php',
        ];
    }
}
