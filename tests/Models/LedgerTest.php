<?php

use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

test('to array', function () {
    $ledger = Ledger::factory()->create()->fresh();

    expect(array_keys($ledger->toArray()))->toBe([
        'id',
        'transaction_id',
        'transaction_line_id',
        'account_id',
        'date',
        'transaction_title',
        'description',
        'debit',
        'credit',
        'debit_balance',
        'credit_balance',
        'created_at',
        'updated_at',
    ]);
});

test('date cast', function () {
    $ledger = Ledger::factory()->create()->fresh();

    expect($ledger->date)->toBeInstanceOf(Carbon\Carbon::class);
});

test('account relation', function () {
    $account = Account::factory()->create()->fresh();
    $ledger = Ledger::factory()->for($account)->create()->fresh();

    expect($ledger->account)->toBeInstanceOf(Account::class)
        ->and($ledger->account->id)->toBe($account->id);
});

test('transaction relation', function () {
    $transaction = Transaction::factory()->create()->fresh();
    $ledger = Ledger::factory()->for($transaction)->create()->fresh();

    expect($ledger->transaction)->toBeInstanceOf(Transaction::class)
        ->and($ledger->transaction->id)->toBe($transaction->id);
});

test('transaction line relation', function () {
    $line = TransactionLine::factory()->create()->fresh();
    $ledger = Ledger::factory()->for($line)->create()->fresh();

    expect($ledger->transactionLine)->toBeInstanceOf(TransactionLine::class)
        ->and($ledger->transactionLine->id)->toBe($line->id);
});
