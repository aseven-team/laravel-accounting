<?php

namespace AsevenTeam\LaravelAccounting\Actions\Transaction;

use AsevenTeam\LaravelAccounting\Commands\SyncLedgerCommand;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DeleteTransaction
{
    public function handle(Transaction $transaction): bool
    {
        return DB::transaction(function () use ($transaction) {
            $date = $transaction->date;

            $status = $transaction->delete();

            Artisan::call(SyncLedgerCommand::class, [
                '--start-date' => $date->format('Y-m-d'),
            ]);

            return $status;
        });
    }
}
