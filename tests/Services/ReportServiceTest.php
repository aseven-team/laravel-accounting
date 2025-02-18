<?php

use AsevenTeam\LaravelAccounting\Actions\Transaction\PostTransactionToLedger;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\Models\TransactionLine;
use AsevenTeam\LaravelAccounting\Services\ReportService;

test('get journal report', function () {
    $transaction1 = Transaction::factory()->create(['date' => now()->subDays(7)]);
    TransactionLine::factory()->for($transaction1)->debit()->create(['debit' => 100]);
    TransactionLine::factory()->for($transaction1)->credit()->create(['credit' => 100]);

    $transaction2 = Transaction::factory()->create(['date' => now()]);
    TransactionLine::factory()->for($transaction2)->debit()->create(['debit' => 200]);
    TransactionLine::factory()->for($transaction2)->credit()->create(['credit' => 200]);

    $service = app(ReportService::class);

    $journals = $service->getJournalReport(
        from: now()->subDays(2)->format('Y-m-d'),
        to: now()->format('Y-m-d'),
    );

    expect($journals->count())->toBe(1)
        ->and($journals->first()->transaction_title)->toBe($transaction2->title)
        ->and($journals->first()->transaction_date->format('Y-m-d'))->toBe($transaction2->date->format('Y-m-d'))
        ->and($journals->first()->items->count())->toBe(2)
        ->and($journals->first()->totalDebit())->toEqual(200)
        ->and($journals->first()->totalCredit())->toEqual(200);
});

test('get general ledger report', function () {
    $account1 = Account::factory()->create(['code' => '20001']);
    $account2 = Account::factory()->create(['code' => '10001']);

    $transaction1 = Transaction::factory()->create(['date' => now()->subDays(7)]);
    TransactionLine::factory()->for($transaction1)->for($account1)->debit()->create(['debit' => 100]);
    TransactionLine::factory()->for($transaction1)->for($account2)->credit()->create(['credit' => 100]);

    $transaction2 = Transaction::factory()->create(['date' => now()]);
    TransactionLine::factory()->for($transaction2)->for($account1)->debit()->create(['debit' => 200]);
    TransactionLine::factory()->for($transaction2)->for($account2)->credit()->create(['credit' => 200]);

    app(PostTransactionToLedger::class)->handle($transaction1);
    app(PostTransactionToLedger::class)->handle($transaction2);

    $service = app(ReportService::class);

    $accountLedgers = $service->getGeneralLedgerReport(
        from: now()->subDays(2)->format('Y-m-d'),
        to: now()->format('Y-m-d'),
    );

    expect($accountLedgers->count())->toBe(2)
        ->and($accountLedgers->first()->account_code)->toBe($account2->code)
        ->and($accountLedgers->first()->account_name)->toBe($account2->name)
        ->and($accountLedgers->first()->ledgers->count())->toBe(1)
        ->and($accountLedgers->first()->starting_debit_balance)->toEqual(0)
        ->and($accountLedgers->first()->starting_credit_balance)->toEqual(100)
        ->and($accountLedgers->first()->ending_debit_balance)->toEqual(0)
        ->and($accountLedgers->first()->ending_credit_balance)->toEqual(300)
        ->and($accountLedgers->last()->account_code)->toBe($account1->code)
        ->and($accountLedgers->last()->account_name)->toBe($account1->name)
        ->and($accountLedgers->last()->ledgers->count())->toBe(1)
        ->and($accountLedgers->last()->starting_debit_balance)->toEqual(100)
        ->and($accountLedgers->last()->starting_credit_balance)->toEqual(0)
        ->and($accountLedgers->last()->ending_debit_balance)->toEqual(300)
        ->and($accountLedgers->last()->ending_credit_balance)->toEqual(0);
});

test('get trial balance report', function () {
    $account1 = Account::factory()->create(['code' => '20001', 'type' => AccountType::Asset]);
    $account2 = Account::factory()->create(['code' => '10001', 'type' => AccountType::Expense]);

    $transaction1 = Transaction::factory()->create(['date' => now()->subDays(7)]);
    TransactionLine::factory()->for($transaction1)->for($account1)->debit()->create(['debit' => 100]);
    TransactionLine::factory()->for($transaction1)->for($account2)->credit()->create(['credit' => 100]);

    $transaction2 = Transaction::factory()->create(['date' => now()]);
    TransactionLine::factory()->for($transaction2)->for($account1)->debit()->create(['debit' => 200]);
    TransactionLine::factory()->for($transaction2)->for($account2)->credit()->create(['credit' => 200]);

    app(PostTransactionToLedger::class)->handle($transaction1);
    app(PostTransactionToLedger::class)->handle($transaction2);

    $service = app(ReportService::class);

    $trialBalance = $service->getTrialBalanceReport(
        from: now()->subDays(2)->format('Y-m-d'),
        to: now()->format('Y-m-d'),
    );

    expect($trialBalance->total_starting_credit_balance)->toEqual(100)
        ->and($trialBalance->total_starting_debit_balance)->toEqual(100)
        ->and($trialBalance->total_credit_movement)->toEqual(200)
        ->and($trialBalance->total_debit_movement)->toEqual(200)
        ->and($trialBalance->total_ending_credit_balance)->toEqual(300)
        ->and($trialBalance->total_ending_debit_balance)->toEqual(300)
        ->and($trialBalance->account_type_balances->count())->toBe(2);
});
