<?php

namespace AsevenTeam\LaravelAccounting\Actions\Account;

use AsevenTeam\LaravelAccounting\Data\Account\CreateAccountData;
use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Models\Account;

class CreateAccount
{
    public function handle(CreateAccountData $data): Account
    {
        return Account::create([
            ...$data->toArray(),
            'status' => AccountStatus::Active,
        ]);
    }
}
