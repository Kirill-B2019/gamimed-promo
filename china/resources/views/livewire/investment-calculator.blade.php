<div>
    @if (! $enabled)
        <p class="text-sm text-paper/60">{{ __('calculator.disabled') }}</p>
    @else
        <div class="grid gap-6 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('calculator.amount') }}</span>
                <input
                    type="number"
                    min="0"
                    step="1"
                    inputmode="decimal"
                    wire:model.live.debounce.400ms.number="amount"
                    class="field-input"
                >
            </label>

            <label class="block">
                <span class="text-sm font-medium text-paper">{{ __('calculator.currency') }}</span>
                <select
                    wire:model.live="currency"
                    class="field-input"
                >
                    @foreach ($currencies as $code)
                        <option value="{{ $code }}">{{ $code }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <dl class="mt-8 grid gap-4 sm:grid-cols-2">
            @if ($currency !== 'USD' && in_array('USD', $currencies, true))
                <div>
                    <dt class="text-sm text-paper/60">{{ __('calculator.amount_usd') }}</dt>
                    <dd class="mt-1 font-display text-2xl font-semibold text-blue-bright">
                        {{ number_format($this->amountUsd, 2) }} USD
                    </dd>
                </div>
            @endif
            @if ($currency !== 'CNY' && in_array('CNY', $currencies, true))
                <div>
                    <dt class="text-sm text-paper/60">{{ __('calculator.amount_cny') }}</dt>
                    <dd class="mt-1 font-display text-2xl font-semibold text-red">
                        {{ number_format($this->amountCny, 2) }} CNY
                    </dd>
                </div>
            @endif
            <div>
                <dt class="text-sm text-paper/60">{{ __('calculator.tokens') }}</dt>
                <dd class="mt-1 font-display text-2xl font-semibold text-gold">
                    {{ number_format($this->tokens, 2) }}
                </dd>
            </div>
            <div>
                <dt class="text-sm text-paper/60">{{ __('calculator.price') }}</dt>
                <dd class="mt-1 text-lg text-paper">
                    {{ number_format($presalePriceUsd, 4) }} USD
                </dd>
            </div>
        </dl>
    @endif
</div>
