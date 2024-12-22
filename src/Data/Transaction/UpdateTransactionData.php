<?php

namespace AsevenTeam\LaravelAccounting\Data\Transaction;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class UpdateTransactionData extends Data
{
    public function __construct(
        public Carbon $date,
        /** @var TransactionLineCollection<int, TransactionLineData> */
        public TransactionLineCollection $lines,
        public ?string $number = null,
        public ?string $description = null,
        public ?Model $reference = null,
    ) {}
}
