<x-filament-panels::page.simple>
    <div class="space-y-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Enter a 6-digit code from your authenticator app, or an unused recovery code.
        </p>
        <form wire:submit="verify" class="space-y-4">
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model="code"
                    inputmode="text"
                    autocomplete="one-time-code"
                    placeholder="123456"
                />
            </x-filament::input.wrapper>
            @error('code')
                <p class="text-sm text-danger-600">{{ $message }}</p>
            @enderror
            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button type="submit">
                    Verify
                </x-filament::button>
            </div>
        </form>
        <form method="POST" action="{{ filament()->getLogoutUrl() }}">
            @csrf
            <x-filament::button type="submit" color="gray">
                Log out
            </x-filament::button>
        </form>
    </div>
</x-filament-panels::page.simple>
