<?php

namespace AsevenTeam\LaravelAccounting\Services;

use AsevenTeam\LaravelAccounting\Data\Report\Journal\JournalEntryData;
use AsevenTeam\LaravelAccounting\Data\Report\Ledger\AccountLedgerData;
use AsevenTeam\LaravelAccounting\Data\Report\TrialBalance\TrialBalanceData;
use AsevenTeam\LaravelAccounting\Models\Ledger;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\QueryBuilders\TransactionQueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Get journal report
     *
     * @param string|null $from
     * @param string|null $to
     * @return Collection<int, JournalEntryData>
     */
    public function getJournalReport(?string $from, ?string $to): Collection
    {
        $transactions = Transaction::query()
            ->with('lines.account')
            ->when($from && $to, function (TransactionQueryBuilder $query) use ($from, $to) {
                $query->period($from, $to);
            })
            ->orderBy('date')
            ->get()
            ->map(fn (Transaction $transaction) => [
                'transaction_id' => $transaction->id,
                'transaction_title' => $transaction->title,
                'transaction_date' => $transaction->date,
                'items' => $transaction->lines->map(fn ($line) => [
                    'account_code' => $line->account->name,
                    'account_name' => $line->account->name,
                    'debit' => $line->debit,
                    'credit' => $line->credit,
                ]),
            ]);

        return JournalEntryData::collect($transactions);
    }

    /**
     * Get general ledger report
     *
     * @param string|null $from
     * @param string|null $to
     * @return Collection<int, AccountLedgerData>
     */
    public function getGeneralLedgerReport(?string $from, ?string $to): Collection
    {
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
            ->groupBy('account.code')
            ->map(fn (Collection $ledgers) => [
                'account_code' => $ledgers->first()->account->code,
                'account_name' => $ledgers->first()->account->name,
                'starting_debit_balance' => $startingBalances->get($ledgers->first()->account_id)?->debit_balance ?? 0,
                'starting_credit_balance' => $startingBalances->get($ledgers->first()->account_id)?->credit_balance ?? 0,
                'ending_debit_balance' => $ledgers->last()->debit_balance,
                'ending_credit_balance' => $ledgers->last()->credit_balance,
                'ledgers' => $ledgers->map(fn (Ledger $ledger) => [
                    'transaction_id' => $ledger->transaction_id,
                    'transaction_title' => $ledger->transaction_title,
                    'transaction_date' => $ledger->date,
                    'description' => $ledger->description,
                    'debit' => $ledger->debit,
                    'credit' => $ledger->credit,
                    'debit_balance' => $ledger->debit_balance,
                    'credit_balance' => $ledger->credit_balance,
                ]),
            ])
            ->sortKeys()
            ->values();

        return AccountLedgerData::collect($ledgers);
    }

    /**
     * Get trial balance report
     *
     * @param string|null $from
     * @param string|null $to
     * @return TrialBalanceData
     */
    public function getTrialBalanceReport(?string $from, ?string $to): TrialBalanceData
    {
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
                $query->where('date', '>=', Carbon::parse($from)->startOfDay())
                    ->where('date', '<=', Carbon::parse($to)->endOfDay());
            })
            ->get()
            ->groupBy('account.type')
            ->map(function (Collection $ledgers) use ($startingBalances) {
                return [
                    'account_type' => $ledgers->first()->account->type->getLabel(),
                    'account_balances' => $ledgers->groupBy('account.code')
                        ->map(fn (Collection $ledgers) => [
                            'account_code' => $ledgers->first()->account->code,
                            'account_name' => $ledgers->first()->account->name,
                            'starting_debit_balance' => $startingBalances->get($ledgers->first()->account_id)?->debit_balance ?? 0,
                            'starting_credit_balance' => $startingBalances->get($ledgers->first()->account_id)?->credit_balance ?? 0,
                            'debit_movement' => $ledgers->sum('debit'),
                            'credit_movement' => $ledgers->sum('credit'),
                            'ending_debit_balance' => $ledgers->last()->debit_balance,
                            'ending_credit_balance' => $ledgers->last()->credit_balance,
                        ]),
                ];
            });

        return TrialBalanceData::from([
            'account_type_balances' => $accountTypes,
            'total_starting_debit_balance' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('starting_debit_balance')),
            'total_starting_credit_balance' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('starting_credit_balance')),
            'total_debit_movement' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('debit_movement')),
            'total_credit_movement' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('credit_movement')),
            'total_ending_debit_balance' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('ending_debit_balance')),
            'total_ending_credit_balance' => $accountTypes->sum(fn ($type) => $type['account_balances']->sum('ending_credit_balance')),
        ]);
    }
}
