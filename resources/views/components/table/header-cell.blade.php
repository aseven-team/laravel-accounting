@php
    use Filament\Support\Enums\Alignment;
@endphp

@props([
    'alignment' => Alignment::Start,
    'wrap' => false,
])

@php
    if (! $alignment instanceof Alignment) {
        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
    }
@endphp

<th
    {{ $attributes->class(['fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6']) }}
>
    <span
        @class([
            'group flex w-full items-center gap-x-1',
            'whitespace-nowrap' => ! $wrap,
            'whitespace-normal' => $wrap,
            match ($alignment) {
                Alignment::Start => 'justify-start',
                Alignment::Center => 'justify-center',
                Alignment::End => 'justify-end',
                Alignment::Left => 'justify-start rtl:flex-row-reverse',
                Alignment::Right => 'justify-end rtl:flex-row-reverse',
                Alignment::Justify, Alignment::Between => 'justify-between',
                default => $alignment,
            },
        ])
    >
        <span
            class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white"
        >
            {{ $slot }}
        </span>
    </span>
</th>
