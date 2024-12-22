<?php

namespace AsevenTeam\LaravelAccounting\QueryBuilders;

use AsevenTeam\LaravelAccounting\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends Builder<Transaction>
 */
final class TransactionQueryBuilder extends Builder
{
    public function period(string $from, string $to): self
    {
        return $this->where('date', '>=', Carbon::parse($from)->startOfDay())
            ->where('date', '<=', Carbon::parse($to)->endOfDay());
    }
}
