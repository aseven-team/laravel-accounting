<x-filament-panels::page>
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($this->reports() as $report)
            <a href="{{ $report['url'] }}">
                <x-filament::section>
                    <div class="flex items-start gap-4">
                        <div
                            @class([
                                'inline-block rounded-lg p-3',
                                match ($report['iconColor']) {
                                    'gray' => 'bg-gray-100 text-gray-700 dark:bg-gray-950 dark:text-gray-500',
                                    default => 'bg-custom-100 text-custom-700 dark:bg-custom-950 dark:text-custom-500',
                                },
                            ])
                            @style([
                                \Filament\Support\get_color_css_variables(
                                    $report['iconColor'],
                                    shades: [100, 500, 700, 950],
                                ) => $report['iconColor'] !== 'gray',
                            ])
                        >
                            <x-filament::icon :icon="$report['icon']" class="h-6 w-6" />
                        </div>

                        <div class="flex-1 grid gap-1">
                            <x-filament::section.heading>
                                {{ $report['title'] }}
                            </x-filament::section.heading>

                            <x-filament::section.description>
                                {{ $report['description'] }}
                            </x-filament::section.description>
                        </div>
                    </div>
                </x-filament::section>
            </a>
        @endforeach
    </div>
</x-filament-panels::page>
