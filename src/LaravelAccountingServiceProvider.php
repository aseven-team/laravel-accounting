<?php

namespace AsevenTeam\LaravelAccounting;

use AsevenTeam\LaravelAccounting\Commands\LaravelAccountingCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAccountingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-accounting')
            ->hasConfigFile()
            ->hasMigrations('create_accounting_tables');
    }

    public function registeringPackage(): void
    {
        $this->app->bind(TransactionCreator::class);
    }
}
