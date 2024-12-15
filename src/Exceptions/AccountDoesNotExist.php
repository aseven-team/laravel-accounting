<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use InvalidArgumentException;

class AccountDoesNotExist extends InvalidArgumentException
{
    public static function create(string $code): static
    {
        return new static("There is no account with code `{$code}`.");
    }
}
