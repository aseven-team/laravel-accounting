<?php

use AsevenTeam\LaravelAccounting\Exceptions\AccountDoesNotExist;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalanceTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

test('create transaction', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $reference = Transaction::factory()->create();

    $transaction = transaction()
        ->withDescription('Test transaction')
        ->setDate($now = now())
        ->withReference($reference)
        ->addLine($account1, 100, 0, 'Debit account 1')
        ->addLine($account2, 0, 100, 'Credit account 2')
        ->save();

    expect($transaction->description)->toBe('Test transaction')
        ->and($transaction->date->format('Y-m-d'))->toBe($now->format('Y-m-d'))
        ->and($transaction->reference->is($reference))->toBeTrue()
        ->and($transaction->lines->count())->toBe(2)
        ->and($transaction->lines->first()->account->is($account1))->toBeTrue()
        ->and($transaction->lines->first()->debit)->toBe(100)
        ->and($transaction->lines->first()->credit)->toBe(0)
        ->and($transaction->lines->first()->description)->toBe('Debit account 1')
        ->and($transaction->lines->last()->account->is($account2))->toBeTrue()
        ->and($transaction->lines->last()->debit)->toBe(0)
        ->and($transaction->lines->last()->credit)->toBe(100)
        ->and($transaction->lines->last()->description)->toBe('Credit account 2');
});

test('create transaction without description', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $reference = Transaction::factory()->create();

    $transaction = transaction()
        ->setDate(now())
        ->withReference($reference)
        ->addLine($account1, 100, 0)
        ->addLine($account2, 0, 100)
        ->save();

    expect($transaction->description)->toBeNull();
});

test('create transaction without reference', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction = transaction()
        ->setDate(now())
        ->addLine($account1, 100, 0)
        ->addLine($account2, 0, 100)
        ->save();

    expect($transaction->reference)->toBeNull();
});

test('create transaction without date', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $this->travelTo($date = now()->subDay());

    $transaction = transaction()
        ->addLine($account1, 100, 0)
        ->addLine($account2, 0, 100)
        ->save();

    expect($transaction->date->format('Y-m-d'))->toBe($date->format('Y-m-d'));
});

test('create transaction with provided number', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction = transaction()
        ->setNumber('INV-001')
        ->addLine($account1, 100, 0)
        ->addLine($account2, 0, 100)
        ->save();

    expect($transaction->number)->toBe('INV-001');
});

test('create transaction with account code', function () {
    $account1 = Account::factory()->create(['code' => '1000']);
    $account2 = Account::factory()->create(['code' => '2000']);

    $transaction = transaction()
        ->addLine('1000', 100, 0)
        ->addLine('2000', 0, 100)
        ->save();

    expect($transaction->lines->count())->toBe(2)
        ->and($transaction->lines->first()->account->is($account1))->toBeTrue()
        ->and($transaction->lines->last()->account->is($account2))->toBeTrue();
});

test('create transaction with invalid account code', function () {
    transaction()
        ->addLine('1000', 100, 0)
        ->save();
})->throws(AccountDoesNotExist::class);

test('create transaction with empty lines', function () {
    transaction()->save();
})->throws(EmptyTransaction::class);

test('create transaction with unbalanced lines', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    transaction()
        ->addLine($account1, 100, 0)
        ->addLine($account2, 0, 50)
        ->save();
})->throws(UnbalanceTransaction::class);

test('update transaction', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $transaction = Transaction::factory()->has(TransactionLine::factory(3), 'lines')->create()->fresh();

    $transaction = transaction($transaction)
        ->withDescription('Updated transaction')
        ->setDate($now = now()->addDays(3))
        ->addLine($account1, 200, 0, 'Debit account 1')
        ->addLine($account2, 0, 200, 'Credit account 2')
        ->save();

    expect($transaction->description)->toBe('Updated transaction')
        ->and($transaction->date->format('Y-m-d'))->toBe($now->format('Y-m-d'))
        ->and($transaction->lines->count())->toBe(2)
        ->and($transaction->lines->first()->account->is($account1))->toBeTrue()
        ->and($transaction->lines->first()->debit)->toBe(200)
        ->and($transaction->lines->first()->credit)->toBe(0)
        ->and($transaction->lines->first()->description)->toBe('Debit account 1')
        ->and($transaction->lines->last()->account->is($account2))->toBeTrue()
        ->and($transaction->lines->last()->debit)->toBe(0)
        ->and($transaction->lines->last()->credit)->toBe(200)
        ->and($transaction->lines->last()->description)->toBe('Credit account 2');
});
