<?php

namespace AsevenTeam\LaravelAccounting\Tests;

use AsevenTeam\LaravelAccounting\Filament\LaravelAccountingFilamentPlugin;
use Filament\Panel;
use Filament\PanelProvider;

class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugin(LaravelAccountingFilamentPlugin::make());
    }
}
