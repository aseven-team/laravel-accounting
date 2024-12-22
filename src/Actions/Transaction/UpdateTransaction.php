<?php

namespace AsevenTeam\LaravelAccounting\Actions\Transaction;

use AsevenTeam\LaravelAccounting\Data\Transaction\UpdateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Models\Transaction;

class UpdateTransaction
{
    public function handle(Transaction $transaction, UpdateTransactionData $data): Transaction
    {
        if ($data->lines->isEmpty()) {
            throw EmptyTransaction::create();
        }

        if (! $data->lines->balanced()) {
            throw UnbalancedTransaction::create();
        }

        $transaction->update([
            'number' => $data->number,
            'date' => $data->date,
            'description' => $data->description,
        ]);

        if ($data->reference) {
            $transaction->reference()->associate($data->reference);
        }

        $transaction->lines()->delete();
        $transaction->lines()->createMany($data->lines->toArray());

        return $transaction;
    }
}
