<?php

use AsevenTeam\LaravelAccounting\Filament\Pages\Reports;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\GeneralLedger;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\Journal;
use AsevenTeam\LaravelAccounting\Filament\Pages\Reports\TrialBalance;
use AsevenTeam\LaravelAccounting\Filament\Resources\StartingBalanceResource\Pages\EditStartingBalances;
use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource\Pages\CreateTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\StartingBalance;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

class ScopedAccount extends Account
{
    protected $table = 'accounts';

    protected static function booted(): void
    {
        // simulates an app scoping accounts per tenant
        static::addGlobalScope('tenant', fn (Builder $query) => $query->where('code', '!=', '9-9999'));
    }
}

beforeEach(function () {
    $this->actingAs((new User)->forceFill(['id' => 1]));
});

dataset('reports', [Reports::class, Journal::class, GeneralLedger::class, TrialBalance::class]);

it('denies report pages when transactions cannot be viewed', function (string $page) {
    Gate::policy(Transaction::class, DenyAllPolicy::class);

    expect($page::canAccess())->toBeFalse();
    Livewire::test($page)->assertForbidden();
})->with('reports');

it('allows report pages when transactions can be viewed', function (string $page) {
    expect($page::canAccess())->toBeTrue();
})->with('reports');

it('rejects starting balances for accounts outside the account scope', function () {
    config()->set('accounting.models.account', ScopedAccount::class);
    $visible = Account::factory()->create(['code' => '1-1001']);
    $hidden = Account::factory()->create(['code' => '9-9999']);

    $page = Livewire::test(EditStartingBalances::class);
    $page->set('data.starting_balances', [
        ['account_id' => $visible->id, 'account' => '', 'debit' => '100', 'credit' => null],
        ['account_id' => $hidden->id, 'account' => '', 'debit' => '999', 'credit' => null],
    ])->call('submit');

    expect(StartingBalance::where('account_id', $hidden->id)->exists())->toBeFalse()
        ->and(StartingBalance::where('account_id', $visible->id)->value('debit'))->toEqual(100);
});

it('rejects transaction lines for accounts outside the account scope', function () {
    config()->set('accounting.models.account', ScopedAccount::class);
    $visible = Account::factory()->create(['code' => '1-1001']);
    $hidden = Account::factory()->create(['code' => '9-9999']);

    Livewire::test(CreateTransaction::class)
        ->fillForm([
            'date' => now()->toDateString(),
            'lines' => [
                ['account_id' => $visible->id, 'debit' => '100', 'credit' => null],
                ['account_id' => $hidden->id, 'debit' => null, 'credit' => '100'],
            ],
        ])
        ->call('create')
        ->assertHasFormErrors(['lines.1.account_id']);

    expect(Transaction::count())->toBe(0);
});

it('does not crash on malformed report filters in the url', function (mixed $filters) {
    Livewire::withQueryParams(['filters' => $filters])
        ->test(Journal::class)
        ->call('applyFilters')
        ->assertOk();
})->with([
    'garbage date' => [['start_date' => 'not-a-date', 'end_date' => '2026-01-01']],
    'array value' => [['start_date' => ['x'], 'end_date' => '2026-01-01']],
]);

class DenyAllPolicy
{
    public function viewAny(): bool
    {
        return false;
    }
}
