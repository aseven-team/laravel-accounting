<?php

use AsevenTeam\LaravelAccounting\Actions\Account\CreateAccount;
use AsevenTeam\LaravelAccounting\Data\Account\CreateAccountData;
use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Models\Account;

test('create an account', function () {
    $action = app(CreateAccount::class);

    $account = $action->handle(CreateAccountData::from([
        'code' => '1001',
        'name' => 'Cash',
        'type' => AccountType::Asset,
        'normal_balance' => NormalBalance::Debit,
        'description' => 'Cash account',
        'parent_id' => null,
    ]));

    expect($account->code)->toBe('1001')
        ->and($account->name)->toBe('Cash')
        ->and($account->type)->toBe(AccountType::Asset)
        ->and($account->normal_balance)->toBe(NormalBalance::Debit)
        ->and($account->status)->toBe(AccountStatus::Active)
        ->and($account->archived_at)->toBeNull()
        ->and($account->description)->toBe('Cash account');
});

test('create an account with parent', function () {
    $action = app(CreateAccount::class);
    $parent = Account::factory()->create();

    $account = $action->handle(CreateAccountData::from([
        'code' => '1001',
        'name' => 'Cash',
        'type' => AccountType::Asset,
        'normal_balance' => NormalBalance::Debit,
        'parent_id' => $parent->id,
    ]));

    expect($account->parent_id)->toBe($parent->id);
});
