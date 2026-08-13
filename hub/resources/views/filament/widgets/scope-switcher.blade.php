<div class="fi-wi-widget">
    <x-filament::section>
        <x-slot name="heading">
            Context scope
        </x-slot>

        <x-slot name="description">
            All / Group / Site — used by resources and stats.
        </x-slot>

        <form wire:submit="apply" class="space-y-4">
            {{ $this->form }}

            <div>
                <x-filament::button type="submit">
                    Apply scope
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</div>
