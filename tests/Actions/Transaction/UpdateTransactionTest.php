<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\UpdateTransaction;
use AsevenTeam\LaravelAccounting\Data\Transaction\UpdateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;

test('update transaction', function () {
    $transaction = Transaction::factory()->create();
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();
    $reference = Transaction::factory()->create();
    $now = now();

    $transaction = app(UpdateTransaction::class)->handle($transaction, UpdateTransactionData::from([
        'date' => $now,
        'number' => '123456',
        'description' => 'Test transaction',
        'reference' => $reference,
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

    expect($transaction->date->format('Y-m-d'))->toBe($now->format('Y-m-d'))
        ->and($transaction->number)->toBe('123456')
        ->and($transaction->description)->toBe('Test transaction')
        ->and($transaction->reference->is($reference))->toBe(true)
        ->and($transaction->lines->count())->toBe(2)
        ->and($transaction->lines->first()->account_id)->toBe($account1->id)
        ->and($transaction->lines->first()->debit)->toBe(100)
        ->and($transaction->lines->first()->credit)->toBe(0)
        ->and($transaction->lines->first()->description)->toBe('Debit account 1')
        ->and($transaction->lines->last()->account_id)->toBe($account2->id)
        ->and($transaction->lines->last()->debit)->toBe(0)
        ->and($transaction->lines->last()->credit)->toBe(100)
        ->and($transaction->lines->last()->description)->toBe('Credit account 2');
});

test('update transaction without lines', function () {
    $transaction = Transaction::factory()->hasLines(3)->create();

    expect(fn () => app(UpdateTransaction::class)->handle($transaction, UpdateTransactionData::from([
        'date' => now(),
        'lines' => [],
    ])))->toThrow(EmptyTransaction::class);
});

test('update transaction with unbalanced lines', function () {
    $transaction = Transaction::factory()->create();
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    expect(fn () => app(UpdateTransaction::class)->handle($transaction, UpdateTransactionData::from([
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
    ])))->toThrow(UnbalancedTransaction::class);
});
