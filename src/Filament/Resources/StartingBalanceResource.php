<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource\Pages\EditStartingBalances;
use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource\Pages\ListStartingBalances;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Number;

class StartingBalanceResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static bool $shouldRegisterNavigation = false;

    public static function getModel(): string
    {
        return Accounting::getAccountClass();
    }

    public static function getModelLabel(): string
    {
        return __('Starting Balance');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->recordUrl(null)
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('startingBalance.debit')
                    ->label(__('Debit'))
                    ->formatStateUsing(fn (?float $state) => $state ? Number::format($state, precision: 2, locale: 'id') : ''),
                TextColumn::make('startingBalance.credit')
                    ->label(__('Credit'))
                    ->formatStateUsing(fn (?float $state) => $state ? Number::format($state, precision: 2, locale: 'id') : ''),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStartingBalances::route('/'),
            'edit' => EditStartingBalances::route('/edit'),
        ];
    }
}
