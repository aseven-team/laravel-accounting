<?php

namespace AsevenTeam\LaravelAccounting\Actions\Account;

use AsevenTeam\LaravelAccounting\Exceptions\AccountHasTransactionsException;
use AsevenTeam\LaravelAccounting\Models\Account;

class DeleteAccount
{
    public function handle(Account $account): void
    {
        if ($account->transactionLines()->exists()) {
            throw AccountHasTransactionsException::create();
        }

        $account->delete();
    }
}
