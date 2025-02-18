<?php

namespace AsevenTeam\LaravelAccounting\Filament;

use AsevenTeam\LaravelAccounting\Filament\Pages\Reports;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\GeneralLedger;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\Journal;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\TrialBalance;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\Field;
use Filament\Panel;

class LaravelAccountingFilamentPlugin implements Plugin
{
    protected ?string $navigationGroup = null;

    public function getId(): string
    {
        return 'laravel-accounting';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                AccountResource::class,
                TransactionResource::class,
            ])
            ->pages([
                Reports::class,
                Journal::class,
                GeneralLedger::class,
                TrialBalance::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        Field::configureUsing(function (Field $field) {
            $field->translateLabel();
        });
    }

    public function navigationGroup(string $navigationGroup): static
    {
        $this->navigationGroup = $navigationGroup;

        return $this;
    }

    public function getNavigationGroup(): ?string
    {
        return $this->navigationGroup;
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
