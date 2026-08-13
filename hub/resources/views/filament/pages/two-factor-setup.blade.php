<x-filament-panels::page.simple>
    @if ($recoveryCodes)
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Save these recovery codes in a password manager. Each code can be used once if you lose your authenticator.
            </p>
            <ul class="mt-4 grid gap-2 font-mono text-sm sm:grid-cols-2">
                @foreach ($recoveryCodes as $recoveryCode)
                    <li class="rounded bg-gray-100 px-3 py-2 dark:bg-gray-800">{{ $recoveryCode }}</li>
                @endforeach
            </ul>
            <div class="mt-6">
                <x-filament::button wire:click="continue">
                    Continue to Hub
                </x-filament::button>
            </div>
        </div>
    @else
        <div class="space-y-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Scan the otpauth URI in an authenticator app, or enter the secret manually, then confirm with a 6-digit code.
            </p>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Secret</p>
                <p class="mt-1 break-all font-mono text-lg tracking-widest">{{ $secret }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">otpauth URI</p>
                <p class="mt-1 break-all font-mono text-xs text-gray-600 dark:text-gray-300">{{ $otpauth }}</p>
            </div>
            <form wire:submit="confirm" class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="text"
                        wire:model="code"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="123456"
                        maxlength="6"
                    />
                </x-filament::input.wrapper>
                @error('code')
                    <p class="text-sm text-danger-600">{{ $message }}</p>
                @enderror
                <x-filament::button type="submit">
                    Confirm and enable
                </x-filament::button>
            </form>
        </div>
    @endif
</x-filament-panels::page.simple>
