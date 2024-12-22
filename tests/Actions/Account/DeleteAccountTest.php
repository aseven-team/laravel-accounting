<?php

use AsevenTeam\LaravelAccounting\Actions\Account\DeleteAccount;
use AsevenTeam\LaravelAccounting\Exceptions\AccountHasTransactionsException;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

it('can delete account', function () {
    $account = Account::factory()->create();

    app(DeleteAccount::class)->handle($account);

    expect(Account::count())->toBe(0);
});

it('can not delete account if it has transactions', function () {
    $account = Account::factory()->create();
    Transaction::factory()
        ->has(TransactionLine::factory(3)->for($account), 'lines')
        ->create();

    expect(fn () => app(DeleteAccount::class)->handle($account))
        ->toThrow(AccountHasTransactionsException::class);
});
