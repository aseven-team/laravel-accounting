<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Filament\Pages\Concerns\HasFilters;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class Journal extends Page
{
    use HasFilters;

    protected static string $view = 'accounting::filament.pages.reports.journal';

    protected static bool $shouldRegisterNavigation = false;

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

    public function getTransactions(): Collection
    {
        return Transaction::query()
            ->with('lines.account')
            ->when($this->filters['start_date'] ?? null, function ($query, $date) {
                $query->where('date', '>=', Carbon::parse($date)->startOfDay());
            })
            ->when($this->filters['end_date'] ?? null, function ($query, $date) {
                $query->where('date', '<=', Carbon::parse($date)->endOfDay());
            })
            ->orderBy('date')
            ->get();
    }
}
