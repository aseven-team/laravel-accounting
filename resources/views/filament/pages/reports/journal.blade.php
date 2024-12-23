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

            <div wire:loading.remove wire:target="applyFilters">
                <x-filament-tables::table>
                    <x-slot:header>
                        <x-filament-tables::header-cell>
                            Account
                        </x-filament-tables::header-cell>
                        <x-filament-tables::header-cell alignment="end">
                            Debit
                        </x-filament-tables::header-cell>
                        <x-filament-tables::header-cell alignment="end">
                            Credit
                        </x-filament-tables::header-cell>
                    </x-slot:header>

                    @foreach($this->getTransactions() as $transaction)
                        <x-filament-tables::row class="bg-gray-50 dark:bg-white/5">
                            <x-filament-tables::cell colspan="3">
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                    <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                        Journal Entry #{{ $transaction->number }} - {{ $transaction->date->format('d/m/Y') }}
                                    </span>
                                </div>
                            </x-filament-tables::cell>
                        </x-filament-tables::row>

                        @foreach($transaction->lines as $line)
                            <x-filament-tables::row>
                                <x-filament-tables::cell>
                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
                                            {{ $line->account->code }} - {{ $line->account->name }}
                                        </span>
                                    </div>
                                </x-filament-tables::cell>

                                <x-filament-tables::cell>
                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                        <span class="fi-ta-text-item-label text-right text-sm leading-6 text-gray-950 dark:text-white">
                                            {{ Number::format($line->debit, precision: 2, locale: 'id') }}
                                        </span>
                                    </div>
                                </x-filament-tables::cell>

                                <x-filament-tables::cell>
                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                        <span class="fi-ta-text-item-label text-right text-sm leading-6 text-gray-950 dark:text-white">
                                            {{ Number::format($line->credit, precision: 2, locale: 'id') }}
                                        </span>
                                    </div>
                                </x-filament-tables::cell>
                            </x-filament-tables::row>
                        @endforeach

                        <x-filament-tables::row>
                            <x-filament-tables::cell>
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                    <span class="fi-ta-text-item-label text-right text-sm font-semibold leading-6 text-gray-950 dark:text-white">
                                        Total
                                    </span>
                                </div>
                            </x-filament-tables::cell>

                            <x-filament-tables::cell>
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                    <span class="fi-ta-text-item-label text-right text-sm font-semibold leading-6 text-gray-950 dark:text-white">
                                        {{ Number::format($transaction->lines->sum('debit'), precision: 2, locale: 'id') }}
                                    </span>
                                </div>
                            </x-filament-tables::cell>

                            <x-filament-tables::cell>
                                <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                                    <span class="fi-ta-text-item-label text-right text-sm font-semibold leading-6 text-gray-950 dark:text-white">
                                        {{ Number::format($transaction->lines->sum('credit'), precision: 2, locale: 'id') }}
                                    </span>
                                </div>
                            </x-filament-tables::cell>
                        </x-filament-tables::row>
                    @endforeach
                </x-filament-tables::table>
            </div>
        </div>
    </x-filament-tables::container>
</x-filament-panels::page>
