@php
    $rawPrice = data_get($settings ?? [], 'presale_price_usd', config('site.settings.presale_price_usd'));
    $price = (float) (is_array($rawPrice) ? ($rawPrice['value'] ?? 0) : $rawPrice);
    $allocations = $section['allocations'] ?? [];
    $vesting = $section['vesting'] ?? [];
    $utility = $section['utility'] ?? [];
@endphp

<section id="tokenomics" class="section-shell relative overflow-hidden bg-sand-deep">
    <div class="pointer-events-none absolute inset-0 bg-pattern-geom opacity-[0.06]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-blockchain opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if ($price > 0)
            <div class="luxury-card mt-10 p-6 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-ink/60">{{ __('ui.presale_price') }}</p>
                <p class="mt-2 font-display text-3xl font-semibold tabular-nums text-gold">
                    {{ number_format($price, 4) }}
                    <span class="text-lg font-medium text-ink/70">USD</span>
                </p>
            </div>
        @endif

        @if (count($allocations))
            <div class="mt-10">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-ink/60">{{ __('ui.allocation') }}</h3>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($allocations as $row)
                        @php($percent = (int) data_get($row, 'percent', 0))
                        <li class="luxury-card">
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <span class="font-medium text-ink">{{ data_get($row, 'label', '') }}</span>
                                <span class="tabular-nums text-gold">{{ $percent }}%</span>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden bg-ink/10">
                                <div
                                    class="h-full bg-gradient-to-r from-emerald to-gold transition-all"
                                    style="width: {{ max(0, min(100, $percent)) }}%"
                                ></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (count($vesting) || count($utility))
            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                @if (count($vesting))
                    <div class="luxury-card">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-ink/60">{{ __('ui.vesting') }}</h3>
                        <ul class="mt-4 space-y-3">
                            @foreach ($vesting as $item)
                                <li class="flex gap-3 text-sm leading-relaxed text-ink/80">
                                    <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald" aria-hidden="true"></span>
                                    <span>{{ is_array($item) ? data_get($item, 'body', data_get($item, 'label', data_get($item, 'title', ''))) : $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (count($utility))
                    <div class="luxury-card">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-ink/60">{{ __('ui.utility') }}</h3>
                        <ul class="mt-4 space-y-3">
                            @foreach ($utility as $item)
                                <li class="flex gap-3 text-sm leading-relaxed text-ink/80">
                                    <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-gold" aria-hidden="true"></span>
                                    <span>{{ is_array($item) ? data_get($item, 'body', data_get($item, 'label', data_get($item, 'title', ''))) : $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif

        <div id="calculator" class="luxury-card mt-12 p-6 sm:p-8">
            <h3 class="font-display text-xl font-semibold text-ink">{{ __('ui.calculator_heading') }}</h3>
            <p class="mt-2 text-sm text-ink/70">{{ __('ui.calculator_lead') }}</p>
            <div class="mt-6">
                <livewire:investment-calculator />
            </div>
        </div>
    </div>
</section>
