<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages;

use AsevenTeam\LaravelAccounting\Actions\Account\CreateAccount;
use AsevenTeam\LaravelAccounting\Data\Account\CreateAccountData;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource;
use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('starting_balance')
                ->label(__('Starting Balance'))
                ->color('gray')
                ->url(StartingBalanceResource::getUrl()),

            Actions\CreateAction::make()
                ->modalWidth('lg')
                ->using(function (array $data) {
                    return app(CreateAccount::class)->handle(CreateAccountData::from($data));
                }),
        ];
    }
}
