<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages;

use AsevenTeam\LaravelAccounting\Filament\LaravelAccountingFilamentPlugin;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\GeneralLedger;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\Journal;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\TrialBalance;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'accounting::filament.pages.reports';

    protected static ?int $navigationSort = 1;

    public static function getNavigationItemActiveRoutePattern(): string
    {
        return static::getRouteName().'*';
    }

    public static function getNavigationGroup(): ?string
    {
        return LaravelAccountingFilamentPlugin::get()->getNavigationGroup();
    }

    public function reports(): array
    {
        return [
            [
                'title' => __('Journal'),
                'description' => __('View all transactions with debit and credit entries.'),
                'url' => Journal::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'iconColor' => 'info',
            ],
            [
                'title' => __('General Ledger'),
                'description' => __('Track transaction history and running balances for all accounts.'),
                'url' => GeneralLedger::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'iconColor' => 'success',
            ],
            [
                'title' => __('Trial Balance'),
                'description' => __('Check if total debits equal total credits across all accounts.'),
                'url' => TrialBalance::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'iconColor' => 'warning',
            ],
        ];
    }
}
