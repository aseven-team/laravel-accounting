<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;

use AsevenTeam\LaravelAccounting\Actions\Transaction\CreateTransaction as CreateTransactionAction;
use AsevenTeam\LaravelAccounting\Data\Transaction\CreateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    /**
     * @throws Halt
     */
    protected function handleRecordCreation(array $data): Model
    {
        try {
            $transaction = app(CreateTransactionAction::class)->handle(CreateTransactionData::from([
                'date' => Carbon::parse($data['date']),
                'number' => $data['number'],
                'description' => $data['description'],
                'lines' => $data['lines'],
            ]));
        } catch (EmptyTransaction|UnbalancedTransaction $exception) {
            Notification::make()
                ->danger()
                ->title(__($exception->getMessage()))
                ->send();

            throw new Halt();
        }

        return $transaction;
    }
}
