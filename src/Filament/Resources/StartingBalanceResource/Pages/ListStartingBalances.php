<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource\Pages;

use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListStartingBalances extends ListRecords
{
    protected static string $resource = StartingBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->translateLabel()
                ->url(StartingBalanceResource::getUrl('edit')),
        ];
    }
}
