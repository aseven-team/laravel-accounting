<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use RuntimeException;

final class AccountHasTransactionsException extends RuntimeException
{
    public static function create(): self
    {
        return new self('Account has transactions, cannot be deleted');
    }
}
