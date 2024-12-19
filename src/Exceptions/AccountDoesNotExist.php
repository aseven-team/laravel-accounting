<?php

namespace AsevenTeam\LaravelAccounting\Exceptions;

use InvalidArgumentException;

final class AccountDoesNotExist extends InvalidArgumentException
{
    public static function create(string $code): self
    {
        return new self("There is no account with code `{$code}`.");
    }
}
