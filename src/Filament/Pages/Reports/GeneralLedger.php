<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Data\Report\Ledger\AccountLedgerData;
use AsevenTeam\LaravelAccounting\Services\ReportService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class GeneralLedger extends BaseReport
{
    protected static string $view = 'accounting::filament.pages.reports.general-ledger';

    public function clearCachedReport(): void
    {
        unset($this->reports);
    }

    /**
     * @return Collection<int, AccountLedgerData>
     */
    #[Computed(persist: true)]
    public function reports(): Collection
    {
        if (! $this->reportLoaded) {
            return collect();
        }

        $from = @$this->filters['start_date'];
        $to = @$this->filters['end_date'];

        return app(ReportService::class)->getGeneralLedgerReport($from, $to);
    }
}
