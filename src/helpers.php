<?php

use AsevenTeam\LaravelAccounting\Models\Transaction;
use AsevenTeam\LaravelAccounting\PendingTransaction;

if (! function_exists('transaction')) {
    function transaction(?Transaction $transaction = null): PendingTransaction
    {
        return new PendingTransaction($transaction);
    }
}
