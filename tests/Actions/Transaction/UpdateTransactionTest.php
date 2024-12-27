<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Actions\Transaction\UpdateTransaction;
use AsevenTeam\LaravelAccounting\Data\Transaction\UpdateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

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

test('updating transaction also updates the ledger', function () {
    $transaction = Transaction::factory()->create();
    $line1 = TransactionLine::factory()->for($transaction)->debit()->create(['debit' => 1000]);
    $line2 = TransactionLine::factory()->for($transaction)->credit()->create(['credit' => 1000]);

    app(PostTransactionToLedger::class)->handle($transaction);

    $transaction = app(UpdateTransaction::class)->handle($transaction, UpdateTransactionData::from([
        'date' => now(),
        'lines' => [
            [
                'account_id' => $line1->account_id,
                'debit' => 2000,
                'credit' => 0,
            ],
            [
                'account_id' => $line2->account_id,
                'debit' => 0,
                'credit' => 2000,
            ],
        ],
    ]));

    $ledgers = Ledger::all();

    expect($ledgers->count())->toBe(2)
        ->and($ledgers->first()->transaction_id)->toBe($transaction->id)
        ->and($ledgers->first()->account_id)->toBe($line1->account_id)
        ->and($ledgers->first()->debit)->toBe(2000)
        ->and($ledgers->first()->credit)->toBe(0)
        ->and($ledgers->last()->transaction_id)->toBe($transaction->id)
        ->and($ledgers->last()->account_id)->toBe($line2->account_id)
        ->and($ledgers->last()->debit)->toBe(0)
        ->and($ledgers->last()->credit)->toBe(2000);
});
