<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use RuntimeException;

class EmptyTransaction extends RuntimeException
{
    public static function create(): static
    {
        return new static('Transaction is empty. Please add at least one line to the transaction.');
    }
}
