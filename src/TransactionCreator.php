<?php

namespace AsevenTeam\LaravelAccounting;

use AsevenTeam\LaravelAccounting\Exceptions\EmptyTransaction;
use AsevenTeam\LaravelAccounting\Exceptions\UnbalanceTransaction;
use AsevenTeam\LaravelAccounting\Models\Account;
use AsevenTeam\LaravelAccounting\Models\Transaction;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TransactionCreator
{
    protected ?Transaction $transaction = null;

    protected Collection $lines;

    public function setDate(DateTimeInterface $date): static
    {
        $this->getTransaction()->date = Carbon::instance($date);

        return $this;
    }

    public function withReference(Model $model): static
    {
        $this->getTransaction()->reference()->associate($model);

        return $this;
    }

    public function withDescription(string $description): static
    {
        $this->getTransaction()->description = $description;

        return $this;
    }

    public function addLine(Account|string $account, float $debit, float $credit, ?string $description = null): self
    {
        $this->getTransaction(); // Ensure transaction is initialized

        if (is_string($account)) {
            $account = Account::findByCode($account);
        }

        $this->lines->push([
            'account_id' => $account->id,
            'debit' => $debit,
            'credit' => $credit,
            'description' => $description,
        ]);

        return $this;
    }

    public function save(): Transaction
    {
        $transaction = $this->getTransaction();

        $this->ensureTransactionIsBalanced();

        if (! $transaction->date) {
            $transaction->date = Carbon::now();
        }

        $transaction->save();

        $transaction->lines()->createMany($this->lines->toArray());

        $this->transaction = null;
        $this->lines = collect();

        return $transaction;
    }

    protected function ensureTransactionIsBalanced(): void
    {
        if ($this->lines->isEmpty()) {
            throw EmptyTransaction::create();
        }

        $totalDebit = $this->lines->sum('debit');
        $totalCredit = $this->lines->sum('credit');

        if ($totalDebit !== $totalCredit) {
            throw UnbalanceTransaction::create();
        }
    }

    protected function getTransaction(): Transaction
    {
        if (! $this->transaction) {
            $this->transaction = new Transaction;
            $this->lines = collect();
        }

        return $this->transaction;
    }
}
