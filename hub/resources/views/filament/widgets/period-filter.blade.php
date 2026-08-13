<div>
    <x-filament::section heading="Stats period">
        <form wire:submit="apply" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1">
                {{ $this->form }}
            </div>
            <x-filament::button type="submit">
                Apply
            </x-filament::button>
        </form>
    </x-filament::section>
</div>
