@php
    use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
@endphp

<x-accounting::report-page>
    <x-accounting::table>
        <x-accounting::table.header>
            <x-accounting::table.header-row :darker="true">
                <x-accounting::table.header-cell>
                    Account
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="end">
                    Debit
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="end">
                    Credit
                </x-accounting::table.header-cell>
            </x-accounting::table.header-row>
        </x-accounting::table.header>

        <x-accounting::table.body>
            @foreach($this->transactions as $transaction)
                <x-accounting::table.group-row>
                    <x-accounting::table.cell colspan="3">
                        <x-filament::link :href="TransactionResource::getUrl('view', ['record' => $transaction])">
                            {{ $transaction->title }}
                        </x-filament::link>
                        - {{ $transaction->date->format('d/m/Y') }}
                    </x-accounting::table.cell>
                </x-accounting::table.group-row>

                @foreach($transaction->lines as $line)
                    <x-accounting::table.row>
                        <x-accounting::table.cell>
                            {{ $line->account->code }} - {{ $line->account->name }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($line->debit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($line->credit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                    </x-accounting::table.row>
                @endforeach

                <x-accounting::table.row>
                    <x-accounting::table.cell class="text-right font-semibold">
                        Total
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($transaction->lines->sum('debit'), precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($transaction->lines->sum('credit'), precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>
            @endforeach
        </x-accounting::table.body>
    </x-accounting::table>
</x-accounting::report-page>
