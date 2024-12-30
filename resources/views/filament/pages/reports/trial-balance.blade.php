<x-accounting::report-page>
    <x-accounting::table>
        <x-accounting::table.header>
            <x-accounting::table.header-row :darker="true">
                <x-accounting::table.header-cell rowspan="2" alignment="center">
                    Account
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell colspan="2" alignment="center">
                    {{ __('Starting Balance') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell colspan="2" alignment="center">
                    {{ __('Movement') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell colspan="2" alignment="center">
                    {{ __('Ending Balance') }}
                </x-accounting::table.header-cell>
            </x-accounting::table.header-row>

            <x-accounting::table.header-row :darker="true">
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Debit') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Credit') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Debit') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Credit') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Debit') }}
                </x-accounting::table.header-cell>
                <x-accounting::table.header-cell alignment="center">
                    {{ __('Credit') }}
                </x-accounting::table.header-cell>
            </x-accounting::table.header-row>
        </x-accounting::table.header>

        <x-accounting::table.body>
            @foreach($this->report['accountTypes'] as $type)
                <x-accounting::table.group-row>
                    <x-accounting::table.cell colspan="7" class="font-semibold">
                        {{ $type['name'] }}
                    </x-accounting::table.cell>
                </x-accounting::table.group-row>

                @foreach($type['accounts'] as $account)
                    <x-accounting::table.row>
                        <x-accounting::table.cell>
                            ({{ $account['code'] }}) {{ $account['name'] }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['starting_debit_balance'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['starting_credit_balance'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['debit_movement'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['credit_movement'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['ending_debit_balance'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                        <x-accounting::table.cell class="text-right">
                            {{ Number::format($account['ending_credit_balance'], precision: 2, locale: 'id') }}
                        </x-accounting::table.cell>
                    </x-accounting::table.row>
                @endforeach
            @endforeach

            <x-accounting::table.group-row>
                <x-accounting::table.cell class="font-semibold">
                    Total
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_starting_debit_balance'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_starting_credit_balance'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_debit_movement'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_credit_movement'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_ending_debit_balance'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
                <x-accounting::table.cell class="text-right font-semibold">
                    {{ Number::format($this->report['total_ending_credit_balance'], precision: 2, locale: 'id') }}
                </x-accounting::table.cell>
            </x-accounting::table.group-row>
        </x-accounting::table.body>
    </x-accounting::table>
</x-accounting::report-page>
