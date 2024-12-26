<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\QueryBuilders\TransactionQueryBuilder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class Journal extends BaseReport
{
    protected static string $view = 'accounting::filament.pages.reports.journal';

    protected function clearCachedReport(): void
    {
        unset($this->transactions);
    }

    #[Computed(persist: true)]
    public function transactions(): Collection
    {
        if (! $this->reportLoaded) {
            return collect();
        }

        $from = @$this->filters['start_date'];
        $to = @$this->filters['end_date'];

        return Transaction::query()
            ->with('lines.account')
            ->when($from && $to, function (TransactionQueryBuilder $query) use ($from, $to) {
                $query->period($from, $to);
            })
            ->orderBy('date')
            ->get();
    }
}
