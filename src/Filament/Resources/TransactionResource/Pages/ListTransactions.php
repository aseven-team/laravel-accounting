<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;

use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
