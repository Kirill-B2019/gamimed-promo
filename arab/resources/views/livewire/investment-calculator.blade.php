<div>
    @if (! $enabled)
        <p class="text-sm text-ink/60">{{ __('calculator.disabled') }}</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('calculator.amount') }}</span>
                <input
                    type="number"
                    min="0"
                    step="1"
                    inputmode="decimal"
                    wire:model.live.debounce.400ms.number="amount"
                    class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 text-ink outline-none focus:border-gold"
                >
            </label>

            <label class="block">
                <span class="text-sm font-medium text-ink">{{ __('calculator.currency') }}</span>
                <select
                    wire:model.live="currency"
                    class="mt-2 w-full border border-ink/15 bg-sand px-3 py-2 text-ink outline-none focus:border-gold"
                >
                    @foreach ($currencies as $code)
                        <option value="{{ $code }}">{{ $code }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <dl class="mt-8 grid gap-4 sm:grid-cols-2">
            @if ($currency !== 'USD')
                <div>
                    <dt class="text-sm text-ink/60">{{ __('calculator.amount_usd') }}</dt>
                    <dd class="mt-1 font-display text-2xl font-semibold text-emerald">
                        {{ number_format($this->amountUsd, 2) }} USD
                    </dd>
                </div>
            @endif
            <div>
                <dt class="text-sm text-ink/60">{{ __('calculator.tokens') }}</dt>
                <dd class="mt-1 font-display text-2xl font-semibold text-gold">
                    {{ number_format($this->tokens, 2) }}
                </dd>
            </div>
            <div>
                <dt class="text-sm text-ink/60">{{ __('calculator.price') }}</dt>
                <dd class="mt-1 text-lg text-ink">
                    {{ number_format($presalePriceUsd, 4) }} USD
                </dd>
            </div>
        </dl>
    @endif
</div>
