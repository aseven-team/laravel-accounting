<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\DeleteTransaction;
use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;

test('delete transaction', function () {
    $transaction = Transaction::factory()->create();

    $status = app(DeleteTransaction::class)->handle($transaction);

    expect($status)->toBeTrue()
        ->and(Transaction::count())->toBe(0);
});

test('delete transaction also sync ledger', function () {
    $transaction = Transaction::factory()->create();
    TransactionLine::factory()->for($transaction)->debit()->create(['debit' => 1000]);
    TransactionLine::factory()->for($transaction)->credit()->create(['credit' => 1000]);

    app(PostTransactionToLedger::class)->handle($transaction);

    app(DeleteTransaction::class)->handle($transaction);

    expect(Ledger::count())->toBe(0);
});
