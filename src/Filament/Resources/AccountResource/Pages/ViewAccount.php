<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages;

use AsevenTeam\LaravelAccounting\Actions\Account\DeleteAccount;
use AsevenTeam\LaravelAccounting\Actions\Account\MarkAccountAsActive;
use AsevenTeam\LaravelAccounting\Actions\Account\MarkAccountAsArchived;
use AsevenTeam\LaravelAccounting\Enums\AccountStatus;
use AsevenTeam\LaravelAccounting\Exceptions\AccountHasTransactionsException;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\EditAction::make()
                    ->modalWidth('lg'),

                Actions\Action::make('archive')
                    ->label(__('Archive'))
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->modalHeading(__('Archive Account'))
                    ->requiresConfirmation()
                    ->successNotificationTitle(__('Account archived'))
                    ->visible(fn (Account $record) => $record->status === AccountStatus::Active)
                    ->action(function (Actions\Action $action, Account $record) {
                        app(MarkAccountAsArchived::class)->handle($record);

                        $action->success();
                    }),

                Actions\Action::make('reactivate')
                    ->label(__('Reactivate'))
                    ->icon('heroicon-o-check-circle')
                    ->modalHeading(__('Reactivate Account'))
                    ->requiresConfirmation()
                    ->successNotificationTitle(__('Account reactivated'))
                    ->visible(fn (Account $record) => $record->status === AccountStatus::Archived)
                    ->action(function (Actions\Action $action, Account $record) {
                        app(MarkAccountAsActive::class)->handle($record);

                        $action->success();
                    }),

                Actions\DeleteAction::make()
                    ->using(function (Actions\DeleteAction $action, Account $record): bool {
                        try {
                            app(DeleteAccount::class)->handle($record);
                        } catch (AccountHasTransactionsException $e) {
                            $action->failureNotificationTitle(__($e->getMessage()));

                            return false;
                        }

                        return true;
                    }),
            ]),
        ];
    }
}
