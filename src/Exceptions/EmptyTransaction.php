<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use RuntimeException;

final class EmptyTransaction extends RuntimeException
{
    public static function create(): self
    {
        return new self('Transaction is empty. Please add at least one line to the transaction.');
    }
}
