<x-filament-panels::page>
    <form
        id="form"
        wire:submit="submit"
        class="fi-form grid gap-y-6"
    >
        {{ $this->form }}

        <x-filament::actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
            class="fi-form-actions"
        />
    </form>
</x-filament-panels::page>
