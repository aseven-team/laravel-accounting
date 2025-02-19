<?php

namespace AsevenTeam\LaravelAccounting;

use AsevenTeam\LaravelAccounting\Commands\SyncLedgerCommand;
use AsevenTeam\LaravelAccounting\Contracts\ReportService;
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
            ->hasCommand(SyncLedgerCommand::class)
            ->hasMigrations([
                'create_accounting_tables',
                'create_ledgers_table',
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ReportService::class, config('accounting.report_service'));
    }
}
