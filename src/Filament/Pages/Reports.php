<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages;

use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\GeneralLedger;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\Journal;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'accounting::filament.pages.reports';

    public static function getNavigationItemActiveRoutePattern(): string
    {
        return static::getRouteName().'*';
    }

    public function reports(): array
    {
        return [
            [
                'title' => __('Journal'),
                'description' => __('View a list of all transactions.'),
                'url' => Journal::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'iconColor' => 'info',
            ],
            [
                'title' => __('General Ledger'),
                'description' => __('View a list of all accounts.'),
                'url' => GeneralLedger::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'iconColor' => 'success',
            ],
        ];
    }
}
