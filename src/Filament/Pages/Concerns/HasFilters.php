<?php

namespace AsevenTeam\LaravelAccounting\Filament\Pages\Concerns;

use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Livewire\Attributes\Url;

/**
 * @mixin InteractsWithForms
 *
 * @property Form $filtersForm
 */
trait HasFilters
{
    #[Url(except: [])]
    public array $filters = [];

    public array $deferredFilters = [];

    public function mountHasFilters(): void
    {
        if (empty($this->filters)) {
            $this->filters = $this->getDefaultFilters();
        }

        $this->filtersForm->fill($this->filters);
    }

    public function getDefaultFilters(): array
    {
        return [];
    }

    public function applyFilters(): void
    {
        $this->filters = $this->filtersForm->getState();
    }

    abstract protected function filtersForm(Form $form): Form;

    protected function getHasFiltersForms(): array
    {
        return [
            'filtersForm' => $this->filtersForm(
                $this->makeForm()
                    ->statePath('deferredFilters')
            ),
        ];
    }

    protected function applyFiltersAction(): Action
    {
        return Action::make('apply_filter')
            ->translateLabel()
            ->button()
            ->action('applyFilters');
    }
}
