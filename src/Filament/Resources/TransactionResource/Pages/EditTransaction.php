<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;

use AsevenTeam\LaravelAccounting\Actions\Transaction\UpdateTransaction;
use AsevenTeam\LaravelAccounting\Data\Transaction\UpdateTransactionData;
use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalancedTransaction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @throws Halt
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        assert($record instanceof Transaction);

        try {
            $transaction = app(UpdateTransaction::class)->handle($record, UpdateTransactionData::from([
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

    protected function getRedirectUrl(): ?string
    {
        return TransactionResource::getUrl('view', ['record' => $this->record]);
    }
}
