@php
    $lines = $evaluate(fn ($get) => $get('lines'));

    $debit = $credit = 0;
    foreach ($lines as $line) {
        $debit += floatval(str_replace(['.', ','], ['', '.'], $line['debit']));
        $credit += floatval(str_replace(['.', ','], ['', '.'], $line['credit']));
    }
@endphp

<div class="flex justify-end gap-2">
    <div class="grid gap-y-2 min-w-36">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Total debit</span>

        <div class="text-sm leading-6">{{ Number::currency($debit, 'IDR', 'id_ID') }}</div>
    </div>

    <div class="grid gap-y-2 min-w-36">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Total credit</span>

        <div class="text-sm leading-6">{{ Number::currency($credit, 'IDR', 'id_ID') }}</div>
    </div>
</div>
