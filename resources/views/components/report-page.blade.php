<x-filament-panels::page>
    <x-filament::section>
        <div class="flex flex-col items-start flex-wrap gap-4 lg:flex-row lg:items-end">
            {{ $this->filtersForm }}

            {{ $this->applyFiltersAction() }}
        </div>
    </x-filament::section>

    <x-filament-tables::container class="overflow-x-auto">
        <div wire:init="applyFilters">
            <div wire:loading.flex wire:target="applyFilters" class="flex items-center justify-center w-full min-h-64">
                <div>
                    <x-filament::loading-indicator class="p-6 text-primary-700 dark:text-primary-300"/>
                </div>
            </div>

            @if ($this->reportLoaded)
                <div wire:loading.remove wire:target="applyFilters">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </x-filament-tables::container>
</x-filament-panels::page>
