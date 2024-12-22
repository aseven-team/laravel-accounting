<?php

namespace AsevenTeam\LaravelAccounting\Enums;

enum AccountStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
}
