<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\TrialBalance;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class AccountTypeBalanceData extends Data
{
    public function __construct(
        public string $account_type,
        /** @var Collection<int, AccountBalanceData> $account_balances */
        public Collection $account_balances,
    )
    {
    }
}
