<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages\ListAccounts;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages\ViewAccount;
use AsevenTeam\LaravelAccounting\Enums\AccountType;
use AsevenTeam\LaravelAccounting\Enums\NormalBalance;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Filament\LaravelAccountingFilamentPlugin;
use AsevenTeam\LaravelAccounting\Filament\Resources\AccountResource\Pages;
use AsevenTeam\LaravelAccounting\Models\Account;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AccountResource extends Resource
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 3;

    public static function getModel(): string
    {
        return Accounting::getAccountClass();
    }

    public static function getNavigationGroup(): ?string
    {
        return LaravelAccountingFilamentPlugin::get()->getNavigationGroup();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema(self::getFormSchema());
    }

    public static function getFormSchema(): array
    {
        return [
            Select::make('type')
                ->options(AccountType::class)
                ->searchable()
                ->disabledOn('edit')
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, Set $set) {
                    $type = AccountType::tryFrom($state);

                    if ($type) {
                        $set('code', $type->getDefaultCodePrefix().'-');
                        $set('normal_balance', $type->getDefaultNormalBalance());
                    }
                }),
            TextInput::make('code')
                ->required()
                ->maxLength(20)
                ->unique(Account::class, ignoreRecord: true),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Select::make('normal_balance')
                ->options(NormalBalance::class)
                ->disabledOn('edit')
                ->required(),
            Select::make('parent_id')
                ->label(__('Sub account of'))
                ->options(fn () => Account::query()->pluck('name', 'id'))
                ->searchable(),
            Textarea::make('description')
                ->nullable()
                ->maxLength(1000),
        ];
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code'),
                        TextEntry::make('name'),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('type'),
                        TextEntry::make('normal_balance'),
                        TextEntry::make('parent')
                            ->label(__('Sub account of'))
                            ->placeholder('-')
                            ->formatStateUsing(fn (?Account $account) => $account ? "($account->code) $account->name" : null),
                        TextEntry::make('description')
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('normal_balance'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(AccountType::class)
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => ListAccounts::route('/'),
            'view' => ViewAccount::route('/{record}'),
        ];
    }
}
