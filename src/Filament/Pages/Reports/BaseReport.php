<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Filament\Pages\Concerns\HasFilters;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;

abstract class BaseReport extends Page
{
    use HasFilters;

    protected static bool $shouldRegisterNavigation = false;

    protected bool $reportLoaded = false;

    abstract protected function clearCachedReport(): void;

    public static function getSlug(): string
    {
        return Reports::getSlug().'/'.parent::getSlug();
    }

    public function getBreadcrumbs(): array
    {
        return [
            Reports::getUrl() => Reports::getNavigationLabel(),
            static::getNavigationLabel(),
        ];
    }

    protected function filtersForm(Form $form): Form
    {
        return $form
            ->columns()
            ->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y'),

                Forms\Components\DatePicker::make('end_date')
                    ->native(false)
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y'),
            ]);
    }

    public function getDefaultFilters(): array
    {
        return [
            'start_date' => now()->startOfMonth()->format('Y-m-d'),
            'end_date' => now()->format('Y-m-d'),
        ];
    }

    protected function afterFiltersApplied(): void
    {
        $this->clearCachedReport();

        $this->reportLoaded = true;
    }
}
