@props([
    'striped' => false,
])

@php
    $stripedClasses = 'bg-gray-50 dark:bg-white/5';
@endphp

<tr
    {{
        $attributes->class([
            'fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75',
            'hover:bg-gray-50 dark:hover:bg-white/5',
            $stripedClasses => $striped,
        ])
    }}
>
    {{ $slot }}
</tr>
