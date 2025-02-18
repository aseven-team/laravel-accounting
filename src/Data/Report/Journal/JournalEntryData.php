<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\Journal;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class JournalEntryData extends Data
{
    public function __construct(
        public int $transaction_id,
        public string $transaction_title,
        public Carbon $transaction_date,
        /** @var Collection<int, JournalEntryItemData> $items */
        public Collection $items,
    ) {}

    public function totalDebit(): float
    {
        return $this->items->sum('debit');
    }

    public function totalCredit(): float
    {
        return $this->items->sum('credit');
    }
}
