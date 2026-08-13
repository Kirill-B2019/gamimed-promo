<div>
    @if (! $enabled)
        <p class="text-sm text-paper/60">{{ __('contact.disabled') }}</p>
    @elseif ($submitted)
        <p class="border border-blue/30 bg-blue/10 px-4 py-3 text-blue-bright" role="status">
            {{ __('contact.thanks') }}
        </p>
    @else
        <form wire:submit="submit" class="space-y-4">
            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('contact.name') }}</span>
                <input type="text" wire:model="name" class="field-input">
                @error('name') <span class="mt-1 block text-sm text-red-bright">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('contact.email') }}</span>
                <input type="email" wire:model="email" class="field-input">
                @error('email') <span class="mt-1 block text-sm text-red-bright">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('contact.phone') }}</span>
                <input type="tel" wire:model="phone" class="field-input">
                @error('phone') <span class="mt-1 block text-sm text-red-bright">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('contact.messenger') }}</span>
                <input type="text" wire:model="messenger" placeholder="WeChat" class="field-input">
                @error('messenger') <span class="mt-1 block text-sm text-red-bright">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('contact.message') }}</span>
                <textarea wire:model="message" rows="3" class="field-input"></textarea>
                @error('message') <span class="mt-1 block text-sm text-red-bright">{{ $message }}</span> @enderror
            </label>

            <button
                type="submit"
                class="cta-luck"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>{{ __('contact.submit') }}</span>
                <span wire:loading>{{ __('contact.sending') }}</span>
            </button>
        </form>
    @endif
</div>
