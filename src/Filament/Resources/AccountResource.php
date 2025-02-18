<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Filament\LaravelAccountingFilamentPlugin;
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
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 3;

    public static function getModel(): string
    {
        return Accounting::getAccountClass();
    }

    public static function getNavigationGroup(): ?string
    {
        return LaravelAccountingFilamentPlugin::get()->getNavigationGroup();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema(self::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('type')
                ->options(AccountType::class)
                ->searchable()
                ->disabledOn('edit')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $type = AccountType::tryFrom($state);

                    if ($type) {
                        $set('code', $type->getDefaultCodePrefix().'-');
                        $set('normal_balance', $type->getDefaultNormalBalance());
                    }
                }),
            Forms\Components\TextInput::make('code')
                ->required()
                ->maxLength(20)
                ->unique(Account::class, ignoreRecord: true),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('normal_balance')
                ->options(NormalBalance::class)
                ->disabledOn('edit')
                ->required(),
            Forms\Components\Select::make('parent_id')
                ->label(__('Sub account of'))
                ->options(fn () => Account::query()->pluck('name', 'id'))
                ->searchable(),
            Forms\Components\Textarea::make('description')
                ->nullable()
                ->maxLength(1000),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Infolists\Components\TextEntry::make('code'),
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('type'),
                        Infolists\Components\TextEntry::make('normal_balance'),
                        Infolists\Components\TextEntry::make('parent')
                            ->label(__('Sub account of'))
                            ->placeholder('-')
                            ->formatStateUsing(fn (?Account $account) => $account ? "($account->code) $account->name" : null),
                        Infolists\Components\TextEntry::make('description')
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('normal_balance'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(AccountType::class)
                    ->searchable(),
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
