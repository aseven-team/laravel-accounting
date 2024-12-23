<?php

namespace AsevenTeam\LaravelAccounting\Filament\Components\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

class MoneyInput extends TextInput
{
    protected function setUp(): void
    {
        $this->prefix('Rp');

        $this->mask(RawJs::make('$money($input, \',\')'));

        $this->stripCharacters('.');

        $this->numeric();
    }
}
