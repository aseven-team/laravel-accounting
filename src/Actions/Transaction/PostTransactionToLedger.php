<?php

namespace AsevenTeam\LaravelAccounting\Actions\Transaction;

use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Support\Facades\DB;

class PostTransactionToLedger
{
    public function handle(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $transaction->load([
                'lines:id,transaction_id,account_id,debit,credit,description',
                'lines.account:id,normal_balance',
            ]);

            foreach ($transaction->lines as $line) {
                $latestLedger = Accounting::getLedgerClass()::query()
                    ->where('account_id', $line->account_id)
                    ->latest('id')
                    ->first();

                $balance = match ($line->account->normal_balance) {
                    NormalBalance::Debit => $latestLedger?->debit_balance - $latestLedger?->credit_balance,
                    NormalBalance::Credit => $latestLedger?->credit_balance - $latestLedger?->debit_balance,
                };

                $addition = match ($line->account->normal_balance) {
                    NormalBalance::Debit => $line->debit - $line->credit,
                    NormalBalance::Credit => $line->credit - $line->debit,
                };

                $balance = $balance + $addition;

                Accounting::getLedgerClass()::create([
                    'transaction_id' => $transaction->id,
                    'transaction_line_id' => $line->id,
                    'account_id' => $line->account_id,
                    'date' => $transaction->date,
                    'transaction_title' => $transaction->title,
                    'description' => $line->description,
                    'debit' => $line->debit,
                    'credit' => $line->credit,
                    'debit_balance' => match ($line->account->normal_balance) {
                        NormalBalance::Debit => $balance > 0 ? $balance : 0,
                        NormalBalance::Credit => $balance < 0 ? abs($balance) : 0,
                    },
                    'credit_balance' => match ($line->account->normal_balance) {
                        NormalBalance::Debit => $balance < 0 ? abs($balance) : 0,
                        NormalBalance::Credit => $balance > 0 ? $balance : 0,
                    },
                ]);
            }
        });
    }
}
