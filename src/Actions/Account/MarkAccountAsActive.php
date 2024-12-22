<?php

namespace AsevenTeam\LaravelAccounting\Actions\Account;

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Models\Account;

class MarkAccountAsActive
{
    public function handle(Account $account): void
    {
        $account->update([
            'status' => AccountStatus::Active,
            'archived_at' => null,
        ]);
    }
}
