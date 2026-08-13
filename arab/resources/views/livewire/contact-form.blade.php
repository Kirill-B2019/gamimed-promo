<div>
    @if (! $enabled)
        <p class="text-sm text-ink/60">{{ __('contact.disabled') }}</p>
    @elseif ($submitted)
        <p class="border border-emerald/30 bg-emerald/10 px-4 py-3 text-emerald" role="status">
            {{ __('contact.thanks') }}
        </p>
    @else
        <form wire:submit="submit" class="space-y-4">
            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('contact.name') }}</span>
                <input type="text" wire:model="name" class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 outline-none focus:border-gold">
                @error('name') <span class="mt-1 block text-sm text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('contact.email') }}</span>
                <input type="email" wire:model="email" class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 outline-none focus:border-gold">
                @error('email') <span class="mt-1 block text-sm text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('contact.phone') }}</span>
                <input type="tel" wire:model="phone" class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 outline-none focus:border-gold">
                @error('phone') <span class="mt-1 block text-sm text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('contact.messenger') }}</span>
                <input type="text" wire:model="messenger" placeholder="WhatsApp" class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 outline-none focus:border-gold">
                @error('messenger') <span class="mt-1 block text-sm text-red-700">{{ $message }}</span> @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('contact.message') }}</span>
                <textarea wire:model="message" rows="4" class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 outline-none focus:border-gold"></textarea>
                @error('message') <span class="mt-1 block text-sm text-red-700">{{ $message }}</span> @enderror
            </label>

            <button
                type="submit"
                class="inline-flex items-center bg-gold px-5 py-3 text-sm font-semibold text-ink transition hover:bg-gold-bright"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>{{ __('contact.submit') }}</span>
                <span wire:loading>{{ __('contact.sending') }}</span>
            </button>
        </form>
    @endif
</div>
