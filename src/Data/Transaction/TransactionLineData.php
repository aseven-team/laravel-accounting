<?php

namespace AsevenTeam\LaravelAccounting\Data\Transaction;

use Spatie\LaravelData\Data;

class TransactionLineData extends Data
{
    public function __construct(
        public int $account_id,
        public float $debit,
        public float $credit,
        public ?string $description = null,
    )
    {
    }
}
