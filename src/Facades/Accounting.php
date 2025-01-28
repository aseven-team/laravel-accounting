<?php

namespace AsevenTeam\LaravelAccounting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AsevenTeam\LaravelAccounting\Accounting
 *
 * @mixin \AsevenTeam\LaravelAccounting\Accounting
 */
class Accounting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AsevenTeam\LaravelAccounting\Accounting::class;
    }
}
