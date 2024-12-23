<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages;
use AsevenTeam\LaravelAccounting\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema(self::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(20)
                ->unique(Account::class, ignoreRecord: true),
            Forms\Components\Select::make('type')
                ->options(AccountType::class)
                ->disabledOn('edit')
                ->required(),
            Forms\Components\Select::make('normal_balance')
                ->options(NormalBalance::class)
                ->disabledOn('edit')
                ->required(),
            Forms\Components\Textarea::make('description')
                ->rows(3)
                ->nullable()
                ->maxLength(1000),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->columns()
                    ->schema([
                        Infolists\Components\TextEntry::make('code'),
                        Infolists\Components\TextEntry::make('status'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('type'),
                        Infolists\Components\TextEntry::make('description')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('normal_balance'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('normal_balance'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'view' => Pages\ViewAccount::route('/{record}'),
        ];
    }
}
