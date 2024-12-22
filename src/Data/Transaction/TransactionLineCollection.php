<?php

namespace AsevenTeam\LaravelAccounting\Data\Transaction;

use Illuminate\Support\Collection;

/**
 * @extends Collection<int, TransactionLineData>
 */
class TransactionLineCollection extends Collection
{
    public function debit(): float
    {
        return $this->sum('debit');
    }

    public function credit(): float
    {
        return $this->sum('credit');
    }

    public function balanced(): bool
    {
        return $this->debit() === $this->credit();
    }
}
