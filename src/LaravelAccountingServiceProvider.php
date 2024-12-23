<?php

namespace AsevenTeam\LaravelAccounting;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAccountingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-accounting')
            ->hasViews()
            ->hasConfigFile()
            ->hasMigrations('create_accounting_tables');
    }
}
