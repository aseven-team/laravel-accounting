<x-filament-tables::cell {{ $attributes }}>
    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
            {{ $slot }}
        </span>
    </div>
</x-filament-tables::cell>
