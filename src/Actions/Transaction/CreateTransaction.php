<?php

namespace AsevenTeam\LaravelAccounting\Actions\Transaction;

use AsevenTeam\LaravelAccounting\Data\Transaction\CreateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CreateTransaction
{
    public function handle(CreateTransactionData $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            if ($data->lines->isEmpty()) {
                throw EmptyTransaction::create();
            }

            if (! $data->lines->balanced()) {
                throw UnbalancedTransaction::create();
            }

            $transaction = Accounting::getTransactionClass()::create([
                'number' => $data->number,
                'date' => $data->date,
                'description' => $data->description,
                'reference_type' => $data->reference?->getMorphClass(),
                'reference_id' => $data->reference?->getKey(),
            ]);

            $transaction->lines()->createMany($data->lines->toArray());

            app(PostTransactionToLedger::class)->handle($transaction);

            return $transaction;
        });
    }
}
