<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

test('post transaction to ledger', function () {
    $transaction = Transaction::factory()->create();
    $line = TransactionLine::factory()->for($transaction)->debit()->create();

    app(PostTransactionToLedger::class)->handle($transaction);

    $ledger = Ledger::first();
    expect(Ledger::count())->toBe(1)
        ->and($ledger->transaction_id)->toBe($transaction->id)
        ->and($ledger->transaction_line_id)->toBe($line->id)
        ->and($ledger->account_id)->toBe($line->account_id)
        ->and($ledger->date->format('Y-m-d'))->toBe($transaction->date->format('Y-m-d'))
        ->and($ledger->transaction_title)->toBe($transaction->title)
        ->and($ledger->description)->toBe($line->description)
        ->and($ledger->debit)->toBe($line->debit)
        ->and($ledger->credit)->toBe($line->credit)
        ->and($ledger->debit_balance)->toBe($line->debit)
        ->and($ledger->credit_balance)->toBe($line->credit);
});

test('balance calculation', function () {
    $account1 = Account::factory()->create();
    $account2 = Account::factory()->create();

    $transaction1 = Transaction::factory()->create();
    $line1 = TransactionLine::factory()->for($account1)->for($transaction1)->debit()->create();
    $line2 = TransactionLine::factory()->for($account2)->for($transaction1)->credit()->create();

    $transaction2 = Transaction::factory()->create();
    $line3 = TransactionLine::factory()->for($account1)->for($transaction2)->debit()->create();
    $line4 = TransactionLine::factory()->for($account2)->for($transaction2)->credit()->create();

    app(PostTransactionToLedger::class)->handle($transaction1);
    app(PostTransactionToLedger::class)->handle($transaction2);

    $ledger1 = Ledger::where('account_id', $account1->id)->latest('id')->first();
    $ledger2 = Ledger::where('account_id', $account2->id)->latest('id')->first();

    expect($ledger1->debit_balance)->toEqual(round($line1->debit + $line3->debit, 2))
        ->and($ledger1->credit_balance)->toEqual(0)
        ->and($ledger2->debit_balance)->toEqual(0)
        ->and($ledger2->credit_balance)->toEqual(round($line2->credit + $line4->credit, 2));
});
