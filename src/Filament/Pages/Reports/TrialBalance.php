<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Reports;

use AsevenTeam\LaravelAccounting\Models\Ledger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;

class TrialBalance extends BaseReport
{
    protected static string $view = 'accounting::filament.pages.reports.trial-balance';

    protected function clearCachedReport(): void
    {
        unset($this->report);
    }

    #[Computed(persist: true)]
    public function report(): array
    {
        $from = $this->filters['start_date'] ? Carbon::parse($this->filters['start_date'])->startOfDay() : null;
        $to = $this->filters['end_date'] ? Carbon::parse($this->filters['end_date'])->endOfDay() : null;

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

        $accountTypes = Ledger::query()
            ->with('account:id,code,name,type')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->where('date', '>=', $from)
                    ->where('date', '<=', $to);
            })
            ->get()
            ->groupBy('account.type')
            ->map(function (Collection $ledgers) use ($startingBalances) {
                return [
                    'name' => $ledgers->first()->account->type->getLabel(),
                    'accounts' => $ledgers->groupBy('account.code')
                        ->map(fn (Collection $ledgers) => [
                            'code' => $ledgers->first()->account->code,
                            'name' => $ledgers->first()->account->name,
                            'starting_debit_balance' => $startingBalances->get($ledgers->first()->account_id)?->debit_balance ?? 0,
                            'starting_credit_balance' => $startingBalances->get($ledgers->first()->account_id)?->credit_balance ?? 0,
                            'debit_movement' => $ledgers->sum('debit'),
                            'credit_movement' => $ledgers->sum('credit'),
                            'ending_debit_balance' => $ledgers->last()->debit_balance,
                            'ending_credit_balance' => $ledgers->last()->credit_balance,
                        ]),
                ];
            });

        return [
            'accountTypes' => $accountTypes,
            'total_starting_debit_balance' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('starting_debit_balance')),
            'total_starting_credit_balance' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('starting_credit_balance')),
            'total_debit_movement' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('debit_movement')),
            'total_credit_movement' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('credit_movement')),
            'total_ending_debit_balance' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('ending_debit_balance')),
            'total_ending_credit_balance' => $accountTypes->sum(fn ($type) => $type['accounts']->sum('ending_credit_balance')),
        ];
    }
}
