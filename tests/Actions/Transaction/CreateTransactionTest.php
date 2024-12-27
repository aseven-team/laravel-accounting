<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\CreateTransaction;
use AsevenTeam\LaravelAccounting\Data\Transaction\CreateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;

test('create transaction', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $now = now();

    $transaction = app(CreateTransaction::class)->handle(CreateTransactionData::from([
        'date' => $now,
        'lines' => [
            [
                'account_id' => $account1->id,
                'debit' => 100,
                'credit' => 0,
            ],
            [
                'account_id' => $account2->id,
                'debit' => 0,
                'credit' => 100,
            ],
        ],
    ]));

    expect($transaction->date->format('Y-m-d'))->toBe($now->format('Y-m-d'))
        ->and($transaction->description)->toBeNull()
        ->and($transaction->reference)->toBeNull()
        ->and($transaction->lines->count())->toBe(2)
        ->and($transaction->lines->first()->account_id)->toBe($account1->id)
        ->and($transaction->lines->first()->debit)->toBe(100)
        ->and($transaction->lines->first()->credit)->toBe(0)
        ->and($transaction->lines->first()->description)->toBeNull()
        ->and($transaction->lines->last()->account_id)->toBe($account2->id)
        ->and($transaction->lines->last()->debit)->toBe(0)
        ->and($transaction->lines->last()->credit)->toBe(100)
        ->and($transaction->lines->last()->description)->toBeNull();
});

test('create transaction with description', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction = app(CreateTransaction::class)->handle(CreateTransactionData::from([
        'date' => now(),
        'description' => 'Test transaction',
        'lines' => [
            [
                'account_id' => $account1->id,
                'debit' => 100,
                'credit' => 0,
                'description' => 'Debit account 1',
            ],
            [
                'account_id' => $account2->id,
                'debit' => 0,
                'credit' => 100,
                'description' => 'Credit account 2',
            ],
        ],
    ]));

    expect($transaction->description)->toBe('Test transaction')
        ->and($transaction->lines->first()->description)->toBe('Debit account 1')
        ->and($transaction->lines->last()->description)->toBe('Credit account 2');
});

test('create transaction with reference', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $reference = Transaction::factory()->create();

    $transaction = app(CreateTransaction::class)->handle(CreateTransactionData::from([
        'date' => now(),
        'reference' => $reference,
        'lines' => [
            [
                'account_id' => $account1->id,
                'debit' => 100,
                'credit' => 0,
            ],
            [
                'account_id' => $account2->id,
                'debit' => 0,
                'credit' => 100,
            ],
        ],
    ]));

    expect($transaction->reference->is($reference))->toBeTrue();
});

test('create transaction with provided number', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction = app(CreateTransaction::class)->handle(CreateTransactionData::from([
        'date' => now(),
        'number' => 'INV-001',
        'lines' => [
            [
                'account_id' => $account1->id,
                'debit' => 100,
                'credit' => 0,
            ],
            [
                'account_id' => $account2->id,
                'debit' => 0,
                'credit' => 100,
            ],
        ],
    ]));

    expect($transaction->number)->toBe('INV-001');
});

test('create empty transaction', function () {
    expect(function () {
        app(CreateTransaction::class)->handle(CreateTransactionData::from([
            'date' => now(),
            'lines' => [],
        ]));
    })->toThrow(EmptyTransaction::class);
});

test('create unbalanced transaction', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    expect(function () use ($account1, $account2) {
        app(CreateTransaction::class)->handle(CreateTransactionData::from([
            'date' => now(),
            'lines' => [
                [
                    'account_id' => $account1->id,
                    'debit' => 100,
                    'credit' => 0,
                ],
                [
                    'account_id' => $account2->id,
                    'debit' => 0,
                    'credit' => 50,
                ],
            ],
        ]));
    })->toThrow(UnbalancedTransaction::class);
});

test('creating transaction always post to ledger', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction = app(CreateTransaction::class)->handle(CreateTransactionData::from([
        'date' => now(),
        'lines' => [
            [
                'account_id' => $account1->id,
                'debit' => 100,
                'credit' => 0,
            ],
            [
                'account_id' => $account2->id,
                'debit' => 0,
                'credit' => 100,
            ],
        ],
    ]));

    expect(Ledger::where('transaction_id', $transaction->id)->count())->toBe(2);
});
