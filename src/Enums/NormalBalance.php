<?php

namespace AsevenTeam\LaravelAccounting\Enums;

use Filament\Support\Contracts\HasLabel;

enum NormalBalance: string implements HasLabel
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Debit => __('Debit'),
            self::Credit => __('Credit'),
        };
    }
}
