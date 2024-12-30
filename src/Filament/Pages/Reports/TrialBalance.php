<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Data\Report\TrialBalance\TrialBalanceData;
use AsevenTeam\LaravelAccounting\Services\ReportService;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

class TrialBalance extends BaseReport
{
    protected static string $view = 'accounting::filament.pages.reports.trial-balance';

    protected function clearCachedReport(): void
    {
        unset($this->report);
    }

    /**
     * @return TrialBalanceData
     */
    #[Computed(persist: true)]
    public function report(): TrialBalanceData
    {
        $from = $this->filters['start_date'] ? Carbon::parse($this->filters['start_date'])->startOfDay() : null;
        $to = $this->filters['end_date'] ? Carbon::parse($this->filters['end_date'])->endOfDay() : null;

        return app(ReportService::class)->getTrialBalanceReport($from, $to);
    }
}
