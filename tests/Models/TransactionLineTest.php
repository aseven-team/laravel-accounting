<?php

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

test('to array', function () {
    $line = TransactionLine::factory()->create()->fresh();

    expect(array_keys($line->toArray()))->toBe([
        'id',
        'transaction_id',
        'account_id',
        'debit',
        'credit',
        'description',
        'created_at',
        'updated_at',
    ]);
});

test('belongs to transaction', function () {
    $transaction = Transaction::factory()->create();
    $line = TransactionLine::factory()->for($transaction)->create();

    expect($line->transaction)->toBeInstanceOf(Transaction::class)
        ->and($line->transaction->id)->toBe($transaction->id);
});

test('belongs to account', function () {
    $account = Account::factory()->create();
    $line = TransactionLine::factory()->for($account)->create();

    expect($account)->toBeInstanceOf(Account::class)
        ->and($account->id)->toBe($line->account_id);
});
