<?php

namespace AsevenTeam\LaravelAccounting;

use AsevenTeam\LaravelAccounting\Commands\LaravelAccountingCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAccountingServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravelaccounting')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravelaccounting_table')
            ->hasCommand(LaravelAccountingCommand::class);
    }
}
