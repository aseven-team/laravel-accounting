@php
    use AsevenTeam\LaravelAccounting\Filament\Resources\TransactionResource;
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
                    Account
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
            @foreach($this->accounts as $account)
                @php
                    $code = $account['code'];
                @endphp

                <x-accounting::table.group-row
                    @click="toggleGroup('{{ $code }}')"
                    class="cursor-pointer"
                >
                    <x-accounting::table.cell colspan="7">
                        <div class="flex items-center gap-2">
                            <template x-if="isGroupExpanded('{{ $code }}')">
                                <x-filament::icon icon="heroicon-o-chevron-up" class="h-4 w-4" />
                            </template>
                            <template x-if="! isGroupExpanded('{{ $code }}')">
                                <x-filament::icon icon="heroicon-o-chevron-down" class="h-4 w-4" />
                            </template>

                            ({{ $account['code'] }}) {{ $account['name'] }}
                        </div>
                    </x-accounting::table.cell>
                </x-accounting::table.group-row>

                <x-accounting::table.row x-show="isGroupExpanded('{{ $code }}')">
                    <x-accounting::table.cell colspan="5" class="text-right font-semibold">
                        Starting balance
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right">
                        {{ Number::format($account['starting_debit_balance'], precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right">
                        {{ Number::format($account['starting_credit_balance'], precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>

                @foreach($account['ledgers'] as $ledger)
                    <x-accounting::table.row x-show="isGroupExpanded('{{ $code }}')">
                        <x-accounting::table.cell>
                            {{ $ledger->date->format('d/M/Y') }}
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

                    <x-accounting::table.cell class="text-right">
                        {{ Number::format($account['ending_debit_balance'], precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>

                    <x-accounting::table.cell class="text-right">
                        {{ Number::format($account['ending_credit_balance'], precision: 2, locale: 'id') }}
                    </x-accounting::table.cell>
                </x-accounting::table.row>
            @endforeach
        </x-accounting::table.body>
    </x-accounting::table>
</x-accounting::report-page>
