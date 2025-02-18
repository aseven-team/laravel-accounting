<?php

namespace AsevenTeam\LaravelAccounting\Actions\Transaction;

use AsevenTeam\LaravelAccounting\Commands\SyncLedgerCommand;
use AsevenTeam\LaravelAccounting\Data\Transaction\UpdateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UpdateTransaction
{
    public function handle(Transaction $transaction, UpdateTransactionData $data): Transaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            if ($data->lines->isEmpty()) {
                throw EmptyTransaction::create();
            }

            if (! $data->lines->balanced()) {
                throw UnbalancedTransaction::create();
            }

            // we need earlier date as starting point for ledger sync
            $earlierDate = $data->date->greaterThan($transaction->date) ? $transaction->date : $data->date;

            $transaction->update([
                'number' => $data->number ?? $transaction->number,
                'date' => $data->date,
                'description' => $data->description,
            ]);

            if ($data->reference) {
                $transaction->reference()->associate($data->reference);
            }

            $transaction->lines()->delete();
            $transaction->lines()->createMany($data->lines->toArray());

            Artisan::call(SyncLedgerCommand::class, [
                '--start-date' => $earlierDate->format('Y-m-d'),
            ]);

            return $transaction;
        });
    }
}
