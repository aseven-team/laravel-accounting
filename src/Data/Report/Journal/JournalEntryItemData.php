<?php

namespace AsevenTeam\LaravelAccounting\Data\Report\Journal;

use Spatie\LaravelData\Data;

class JournalEntryItemData extends Data
{
    public function __construct(
        public string $account_code,
        public string $account_name,
        public float $debit,
        public float $credit,
    ) {}
}
