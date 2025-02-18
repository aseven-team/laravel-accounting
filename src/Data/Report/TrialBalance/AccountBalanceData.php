<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\TrialBalance;

use Spatie\LaravelData\Data;

class AccountBalanceData extends Data
{
    public function __construct(
        public string $account_code,
        public string $account_name,
        public float $starting_debit_balance,
        public float $starting_credit_balance,
        public float $debit_movement,
        public float $credit_movement,
        public float $ending_debit_balance,
        public float $ending_credit_balance,
    ) {}
}
