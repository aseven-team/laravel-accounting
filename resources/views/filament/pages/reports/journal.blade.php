@php
    use AsevenTeam\LaravelAccounting\Data\Report\Journal\JournalEntryData;
    use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
    use Illuminate\Support\Collection;
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
            @php
                /** @var Collection<int, JournalEntryData> $reports */
                $reports = $this->reports;
            @endphp

            @foreach($reports as $journal)
                <x-accounting::table.group-row>
                    <x-accounting::table.cell colspan="3">
                        <x-filament::link :href="TransactionResource::getUrl('view', ['record' => $journal->transaction_id])">
                            {{ $journal->transaction_title }}
                        </x-filament::link>
                        - {{ $journal->transaction_date->format('d/m/Y') }}
                    </x-accounting::table.cell>
                </x-accounting::table.group-row>

                @foreach($journal->items as $item)
                    <x-accounting::table.row>
                        <x-accounting::table.cell>
                            {{ $item->account_code }} - {{ $item->account_name }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($item->debit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($item->credit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                    </x-accounting::table.row>
                @endforeach

                <x-accounting::table.row>
                    <x-accounting::table.cell class="text-right font-semibold">
                        Total
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($journal->totalDebit(), precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($journal->totalCredit(), precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>
            @endforeach
        </x-accounting::table.body>
    </x-accounting::table>
</x-accounting::report-page>
