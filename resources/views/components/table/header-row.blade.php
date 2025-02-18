@props([
    'darker' => false,
])

<tr
    {{ $attributes->class([
        'bg-gray-50 dark:bg-white/5' => ! $darker,
        'bg-gray-100 dark:bg-transparent' => $darker,
    ]) }}
>
    {{ $slot }}
</tr>
