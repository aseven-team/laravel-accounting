<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Models\Ledger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class GeneralLedger extends BaseReport
{
    protected static string $view = 'accounting::filament.pages.reports.general-ledger';

    public function clearCachedReport(): void
    {
        unset($this->accounts);
    }

    #[Computed(persist: true)]
    public function accounts(): Collection
    {
        if (! $this->reportLoaded) {
            return collect();
        }

        $from = @$this->filters['start_date'];
        $to = @$this->filters['end_date'];

        $startingBalances = Ledger::query()
            ->joinSub(
                Ledger::query()
                    ->selectRaw('max(id) as max_id')
                    ->where('date', '<', Carbon::parse($from)->startOfDay())
                    ->groupBy('account_id'),
                'starting_balances',
                'ledgers.id',
                '=',
                'starting_balances.max_id'
            )
            ->select('id', 'account_id', 'debit_balance', 'credit_balance')
            ->get()
            ->keyBy('account_id');

        $ledgers = Ledger::query()
            ->with('account:id,code,name')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->where('date', '>=', Carbon::parse($from)->startOfDay())
                    ->where('date', '<=', Carbon::parse($to)->endOfDay());
            })
            ->get()
            ->groupBy('account.code');

        return $ledgers
            ->map(function (Collection $ledgers) use ($startingBalances) {
                return [
                    'code' => $ledgers->first()->account->code,
                    'name' => $ledgers->first()->account->name,
                    'ledgers' => $ledgers,
                    'starting_debit_balance' => $startingBalances->get($ledgers->first()->account_id)?->debit_balance ?? 0,
                    'starting_credit_balance' => $startingBalances->get($ledgers->first()->account_id)?->credit_balance ?? 0,
                    'ending_debit_balance' => $ledgers->last()->debit_balance,
                    'ending_credit_balance' => $ledgers->last()->credit_balance,
                ];
            })
            ->sortKeys()
            ->values();
    }
}
