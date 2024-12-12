<?php

namespace AsevenTeam\LaravelAccounting\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AsevenTeam\LaravelAccounting\LaravelAccounting
 */
class LaravelAccounting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AsevenTeam\LaravelAccounting\LaravelAccounting::class;
    }
}
