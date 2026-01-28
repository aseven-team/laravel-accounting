<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources;

use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages\ListTransactions;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages\CreateTransaction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages\ViewTransaction;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages\EditTransaction;
use AsevenTeam\LaravelAccounting\Actions\Account\CreateAccount;
use AsevenTeam\LaravelAccounting\Data\Account\CreateAccountData;
use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Filament\Components\Forms\MoneyInput;
use AsevenTeam\LaravelAccounting\Filament\LaravelAccountingFilamentPlugin;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $slug = 'transactions';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function getModel(): string
    {
        return Accounting::getTransactionClass();
    }

    public static function getNavigationGroup(): ?string
    {
        return LaravelAccountingFilamentPlugin::get()->getNavigationGroup();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns()
                    ->schema([
                        TextInput::make('number')
                            ->nullable()
                            ->placeholder('[Auto]')
                            ->maxLength(20),

                        DatePicker::make('date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d/m/Y'),

                        Repeater::make('lines')
                            ->afterStateHydrated(function (?Transaction $record, Repeater $component) {
                                if ($record && $record->lines->isNotEmpty()) {
                                    $component->state($record->lines->map(function ($line) {
                                        return [
                                            'account_id' => $line->account_id,
                                            'description' => $line->description,
                                            'debit' => $line->debit,
                                            'credit' => $line->credit,
                                        ];
                                    })->toArray());
                                }
                            })
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->defaultItems(2)
                            ->deletable(fn (Get $get) => count($get('lines')) > 2)
                            ->reorderable(false)
                            ->table([
                                Repeater\TableColumn::make(__('Account'))
                                    ->width('40%'),
                                Repeater\TableColumn::make(__('Description'))
                                    ->width('20%'),
                                Repeater\TableColumn::make(__('Debit'))
                                    ->width('20%'),
                                Repeater\TableColumn::make(__('Credit'))
                                    ->width('20%'),
                            ])
                            ->schema([
                                Select::make('account_id')
                                    ->options(function () {
                                        return Accounting::getAccountClass()::query()
                                            ->select(['id', 'code', 'name'])
                                            ->get()
                                            ->mapWithKeys(function (Account $account) {
                                                return [$account->id => "{$account->code} - {$account->name}"];
                                            });
                                    })
                                    ->required()
                                    ->searchable()
                                    ->createOptionForm(AccountResource::getFormSchema())
                                    ->createOptionUsing(function (array $data) {
                                        $account = app(CreateAccount::class)->handle(CreateAccountData::from($data));

                                        return $account->id;
                                    })
                                    ->createOptionAction(function (Action $action) {
                                        return $action
                                            ->modalWidth('lg')
                                            ->modalHeading(__('Create Account'));
                                    }),
                                TextInput::make('description')
                                    ->maxLength(200),
                                MoneyInput::make('debit')
                                    ->placeholder('0')
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->live(onBlur: true),
                                MoneyInput::make('credit')
                                    ->placeholder('0')
                                    ->dehydrateStateUsing(fn ($state) => $state ?? 0)
                                    ->live(onBlur: true),
                            ]),

                        Textarea::make('description')
                            ->nullable()
                            ->rows(3)
                            ->maxLength(1000),

                        ViewField::make('total_debit_credit')
                            ->view('accounting::filament.components.total-debit-credit'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date('d/m/Y'),
                TextColumn::make('number')
                    ->prefix('#'),
                TextColumn::make('description')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'view' => ViewTransaction::route('/{record}'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
