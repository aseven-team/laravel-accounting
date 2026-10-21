<td {{ $attributes->class(['fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3']) }}>
    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-3">
        <span class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white">
            {{ $slot }}
        </span>
    </div>
</td>
