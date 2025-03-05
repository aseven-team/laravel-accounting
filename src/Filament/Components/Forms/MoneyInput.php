<?php

namespace AsevenTeam\LaravelAccounting\Filament\Components\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Support\Number;

class MoneyInput extends TextInput
{
    protected function setUp(): void
    {
        $this->prefix('Rp');

        $this->mask(RawJs::make('$money($input, \',\')'));

        $this->mutateStateForValidationUsing(fn (?string $state): ?float => filled($state) ? (float) str_replace(['.', ','], ['', '.'], $state) : null);

        $this->dehydrateStateUsing(fn (?string $state): ?float => filled($state) ? (float) str_replace(['.', ','], ['', '.'], $state) : null);

        $this->formatStateUsing(fn (?float $state): ?string => $state ? Number::format($state, maxPrecision: 2, locale: 'id') : null);

        $this->numeric();
    }
}
