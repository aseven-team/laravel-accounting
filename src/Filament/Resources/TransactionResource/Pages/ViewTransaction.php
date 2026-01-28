<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;

use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
