<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\TrialBalance;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class TrialBalanceData extends Data
{
    public function __construct(
        /** @var Collection<int, AccountTypeBalanceData> $account_type_balances */
        public Collection $account_type_balances,
        public float $total_starting_debit_balance,
        public float $total_starting_credit_balance,
        public float $total_debit_movement,
        public float $total_credit_movement,
        public float $total_ending_debit_balance,
        public float $total_ending_credit_balance,
    )
    {
    }
}
