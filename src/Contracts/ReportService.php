<?php

namespace AsevenTeam\LaravelAccounting\Contracts;

use AsevenTeam\LaravelAccounting\Data\Report\Journal\JournalEntryData;
use AsevenTeam\LaravelAccounting\Data\Report\Ledger\AccountLedgerData;
use AsevenTeam\LaravelAccounting\Data\Report\TrialBalance\TrialBalanceData;
use Illuminate\Support\Collection;

interface ReportService
{
    /**
     * Get journal report
     *
     * @return Collection<int, JournalEntryData>
     */
    public function getJournalReport(?string $from, ?string $to): Collection;

    /**
     * Get general ledger report
     *
     * @return Collection<int, AccountLedgerData>
     */
    public function getGeneralLedgerReport(?string $from, ?string $to): Collection;

    /**
     * Get trial balance report
     */
    public function getTrialBalanceReport(?string $from, ?string $to): TrialBalanceData;
}
