<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use RuntimeException;

class UnbalanceTransaction extends RuntimeException
{
    public static function create(): static
    {
        return new static('The transaction is unbalanced. Debits and credits must be equal.');
    }
}
