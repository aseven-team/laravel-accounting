<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\Ledger;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class LedgerData extends Data
{
    public function __construct(
        public string $transaction_id,
        public string $transaction_title,
        public Carbon $transaction_date,
        public ?string $description,
        public float $debit,
        public float $credit,
        public float $debit_balance,
        public float $credit_balance,
    ) {}
}
