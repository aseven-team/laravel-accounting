<?php

namespace AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource\Pages;

use AsevenTeam\LaravelAccounting\Facades\Accounting;
use AsevenTeam\LaravelAccounting\Filament\Components\Forms\MoneyInput;
use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource;
use AsevenTeam\LaravelAccounting\Models\Account;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Js;
use JsonException;

/**
 * @property Form $form
 */
class EditStartingBalances extends Page
{
    use InteractsWithFormActions;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public ?string $previousUrl = null;

    protected static string $resource = StartingBalanceResource::class;

    protected static string $view = 'accounting::filament.resources.starting-balance-resource.pages.edit-starting-balances';

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    private function fillForm(): void
    {
        $accounts = Accounting::getAccountClass()::query()
            ->with('startingBalance:id,account_id,debit,credit')
            ->get();

        $this->form->fill([
            'starting_balances' => $accounts->map(fn (Account $account) => [
                'account_id' => $account->id,
                'account' => "$account->code - $account->name",
                'debit' => $account->startingBalance?->debit,
                'credit' => $account->startingBalance?->credit,
            ]),
        ]);
    }

    private function authorizeAccess(): void
    {
        abort_unless(StartingBalanceResource::canCreate(), 403);
    }

    public function submit(): void
    {
        $this->authorizeAccess();

        /** @var array{starting_balances: array<int, array{account_id: int, debit: ?float, credit: ?float}>} $data */
        $data = $this->form->getState();

        foreach ($data['starting_balances'] as $startingBalance) {
            if ($startingBalance['debit'] === null && $startingBalance['credit'] === null) {
                continue;
            }

            Accounting::getStartingBalanceClass()::query()->updateOrCreate(
                ['account_id' => $startingBalance['account_id']],
                [
                    'debit' => $startingBalance['debit'] ?? 0,
                    'credit' => $startingBalance['credit'] ?? 0,
                ],
            );
        }

        $this->redirect(StartingBalanceResource::getUrl(), FilamentView::hasSpaMode());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TableRepeater::make('starting_balances')
                            ->hiddenLabel()
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false)
                            ->headers([
                                Header::make(__('Account')),
                                Header::make(__('Debit'))->width('240px'),
                                Header::make(__('Credit'))->width('240px'),
                            ])
                            ->schema([
                                Hidden::make('account_id'),
                                Hidden::make('account')
                                    ->dehydrated(false),
                                Placeholder::make('account')
                                    ->hiddenLabel()
                                    ->content(fn (Get $get) => $get('account')),
                                MoneyInput::make('debit'),
                                MoneyInput::make('credit'),
                            ]),
                    ]),
            ]);
    }

    /**
     * @return array<Action | ActionGroup>
     *
     * @throws JsonException
     */
    public function getFormActions(): array
    {
        return [
            Action::make('submit')
                ->submit('submit')
                ->keyBindings(['mod+s']),

            Action::make('cancel')
                ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = '.Js::from($this->previousUrl ?? self::getResource()::getUrl()).')')
                ->color('gray'),
        ];
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->statePath('data'),
            ),
        ];
    }
}
