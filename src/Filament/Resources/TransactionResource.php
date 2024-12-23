<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use AsevenTeam\LaravelAccounting\Filament\Components\Forms\MoneyInput;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $slug = 'transactions';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns()
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->nullable()
                            ->placeholder('[Auto]')
                            ->maxLength(20),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        TableRepeater::make('lines')
                            ->formatStateUsing(function (?Transaction $transaction, TableRepeater $component) {
                                if ($transaction->lines->isEmpty()) {
                                    return $component->getDefaultState();
                                }

                                return $transaction->lines->map(function ($line) {
                                    return [
                                        'account_id' => $line->account_id,
                                        'description' => $line->description,
                                        'debit' => $line->debit,
                                        'credit' => $line->credit,
                                    ];
                                })->toArray();
                            })
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->defaultItems(2)
                            ->deletable(fn (Forms\Get $get) => count($get('lines')) > 2)
                            ->reorderable(false)
                            ->headers([
                                Header::make('account')
                                    ->label(__('Account'))
                                    ->width('40%'),
                                Header::make('description')
                                    ->label(__('Description'))
                                    ->width('20%'),
                                Header::make('debit')
                                    ->label(__('Debit'))
                                    ->width('20%'),
                                Header::make('credit')
                                    ->label(__('Credit'))
                                    ->width('20%'),
                            ])
                            ->schema([
                                Forms\Components\Select::make('account_id')
                                    ->options(function () {
                                        return Account::query()
                                            ->select('id', 'code', 'name')
                                            ->get()
                                            ->mapWithKeys(function (Account $account) {
                                                return [$account->id => "{$account->code} - {$account->name}"];
                                            });
                                    })
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('description')
                                    ->maxLength(200),
                                MoneyInput::make('debit')
                                    ->placeholder(0)
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->live(onBlur: true),
                                MoneyInput::make('credit')
                                    ->placeholder(0)
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->live(onBlur: true),
                            ]),

                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\ViewField::make('total_debit_credit')
                            ->view('accounting::filament.components.total-debit-credit'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('number')
                    ->prefix('#'),
                Tables\Columns\TextColumn::make('description')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
