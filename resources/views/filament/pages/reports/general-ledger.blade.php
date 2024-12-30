@php
    use AsevenTeam\LaravelAccounting\Data\Report\Ledger\AccountLedgerData;
    use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
    use Illuminate\Support\Collection;
@endphp

<x-accounting::report-page>
    <x-accounting::table
        x-data="{
            expandedGroups: [],
            isGroupExpanded(group) {
                return this.expandedGroups.includes(group);
            },
            toggleGroup(group) {
                if (this.isGroupExpanded(group)) {
                    this.expandedGroups = this.expandedGroups.filter((g) => g !== group);
                } else {
                    this.expandedGroups.push(group);
                }
            },
        }"
    >
        <x-accounting::table.header>
            <x-accounting::table.header-row :darker="true">
                <x-accounting::table.header-cell rowspan="2">
                    Date
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell rowspan="2">
                    Transaction
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell rowspan="2">
                    Description
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center" rowspan="2">
                    Debit
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center" rowspan="2">
                    Credit
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center" colspan="2">
                    Balance
                </x-accounting::table.header-cell>
            </x-accounting::table.header-row>

            <x-accounting::table.header-row :darker="true">
                <x-accounting::table.header-cell alignment="center">
                    Debit
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    Credit
                </x-accounting::table.header-cell>
            </x-accounting::table.header-row>
        </x-accounting::table.header>

        <x-accounting::table.body>
            @php
                /** @var Collection<int, AccountLedgerData> $reports */
                $reports = $this->reports;
            @endphp

            @foreach($reports as $report)
                <x-accounting::table.group-row
                    @click="toggleGroup('{{ $report->account_code }}')"
                    class="cursor-pointer"
                >
                    <x-accounting::table.cell colspan="7">
                        <div class="flex items-center gap-2">
                            <template x-if="isGroupExpanded('{{ $report->account_code }}')">
                                <x-filament::icon icon="heroicon-o-chevron-up" class="h-4 w-4" />
                            </template>
                            <template x-if="! isGroupExpanded('{{ $report->account_code }}')">
                                <x-filament::icon icon="heroicon-o-chevron-down" class="h-4 w-4" />
                            </template>

                            ({{ $report->account_code }}) {{ $report->account_name }}
                        </div>
                    </x-accounting::table.cell>
                </x-accounting::table.group-row>

                <x-accounting::table.row x-show="isGroupExpanded('{{ $report->account_code }}')">
                    <x-accounting::table.cell colspan="5" class="text-right font-semibold">
                        Starting balance
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($report->starting_debit_balance, precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($report->starting_credit_balance, precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>

                @foreach($report->ledgers as $ledger)
                    <x-accounting::table.row x-show="isGroupExpanded('{{ $report->account_code }}')">
                        <x-accounting::table.cell>
                            {{ $ledger->transaction_date->format('d/M/Y') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell>
                            <x-filament::link :href="TransactionResource::getUrl('view', ['record' => $ledger->transaction_id])">
                                {{ $ledger->transaction_title }}
                            </x-filament::link>
                        </x-accounting::table.cell>

                        <x-accounting::table.cell>
                            {{ $ledger->description }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($ledger->debit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($ledger->credit, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($ledger->debit_balance, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>

                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($ledger->credit_balance, precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                    </x-accounting::table.row>
                @endforeach

                <x-accounting::table.row>
                    <x-accounting::table.cell colspan="5" class="text-right font-semibold">
                        Ending balance
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($report->ending_debit_balance, precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right font-semibold">
                        {{ Number::format($report->ending_credit_balance, precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>
            @endforeach
        </x-accounting::table.body>
    </x-accounting::table>
</x-accounting::report-page>
