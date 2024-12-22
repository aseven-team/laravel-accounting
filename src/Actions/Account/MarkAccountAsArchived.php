<?php

namespace AsevenTeam\LaravelAccounting\Actions\Account;

use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Models\Account;

class MarkAccountAsArchived
{
    public function handle(Account $account): void
    {
        $account->update([
            'status' => AccountStatus::Archived,
            'archived_at' => now(),
        ]);
    }
}
