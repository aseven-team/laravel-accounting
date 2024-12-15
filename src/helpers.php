<?php

use AsevenTeam\LaravelAccounting\TransactionCreator;

if (! function_exists('transaction')) {
    function transaction(): TransactionCreator
    {
        return app(TransactionCreator::class);
    }
}
