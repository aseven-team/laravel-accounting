<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use RuntimeException;

final class UnbalanceTransaction extends RuntimeException
{
    public static function create(): self
    {
        return new self('The transaction is unbalanced. Debits and credits must be equal.');
    }
}
