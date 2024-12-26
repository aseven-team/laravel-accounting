<x-accounting::report-page>
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

        @foreach($this->transactions as $transaction)
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

                <x-filament-tables::cell class="text-right">
                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
                        <span class="fi-ta-text-item-label text-sm font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ Number::format($transaction->lines->sum('credit'), precision: 2, locale: 'id') }}
                        </span>
                    </div>
                </x-filament-tables::cell>
            </x-filament-tables::row>
        @endforeach
    </x-filament-tables::table>
</x-accounting::report-page>
