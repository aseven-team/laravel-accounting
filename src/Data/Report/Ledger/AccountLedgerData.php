<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\Ledger;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class AccountLedgerData extends Data
{
    public function __construct(
        public string $account_code,
        public string $account_name,
        public float $starting_debit_balance,
        public float $starting_credit_balance,
        public float $ending_debit_balance,
        public float $ending_credit_balance,
        /** @var Collection<int, LedgerData> $ledgers */
        public Collection $ledgers,
    ) {}
}
