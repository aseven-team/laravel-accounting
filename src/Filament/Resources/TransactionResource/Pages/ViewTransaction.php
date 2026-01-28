<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;

use Filament\Actions\EditAction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
use Filament\Actions;
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
