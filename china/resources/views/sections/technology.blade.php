@php
    $rawLayers = $section['layers'] ?? $section['stack'] ?? $section['items'] ?? [];
    $layers = [];
    foreach ($rawLayers as $layer) {
        if (is_string($layer) && $layer !== '') {
            $layers[] = ['title' => $layer, 'body' => ''];
        } elseif (is_array($layer)) {
            $layers[] = $layer;
        }
    }
    $capabilities = $section['capabilities'] ?? [];
    $accents = [
        ['badge' => 'bg-blue text-paper', 'card' => 'border-blue/30'],
        ['badge' => 'bg-gold text-ink', 'card' => 'border-gold/25'],
        ['badge' => 'bg-red text-paper', 'card' => 'border-red/25'],
    ];
    $layerNumbers = [1, 2, 3, 5, 6, 8, 9];
@endphp

<section id="technology" class="section-shell relative overflow-hidden border-y border-blue/15 bg-gradient-to-br from-sand via-sand to-blue/10">
    <div class="pointer-events-none absolute inset-0 bg-pattern-cloud opacity-[0.08]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-tech opacity-[0.16]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-y-0 start-0 w-1.5 bg-blue" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($layers))
            <ol class="relative mt-10 space-y-4">
                @foreach ($layers as $index => $layer)
                    @php($accent = $accents[$index % count($accents)])
                    <li class="relative flex gap-4 sm:gap-6">
                        <div class="flex w-12 shrink-0 flex-col items-center">
                            <span class="flex h-10 w-10 items-center justify-center font-display text-sm font-semibold {{ $accent['badge'] }}">
                                {{ sprintf('%02d', $layerNumbers[$index] ?? ($index + 2)) }}
                            </span>
                            @if (! $loop->last)
                                <span class="mt-2 w-px flex-1 bg-blue/40" aria-hidden="true"></span>
                            @endif
                        </div>
                        <div class="surface-card flex-1 {{ $accent['card'] }}">
                            <h3 class="font-semibold text-paper">{{ data_get($layer, 'title', '') }}</h3>
                            @if (data_get($layer, 'body'))
                                <p class="mt-2 text-sm leading-relaxed text-paper/75">{{ $layer['body'] }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif

        @if (count($capabilities))
            <ul class="mt-8 flex flex-wrap gap-2">
                @foreach ($capabilities as $capability)
                    <li class="border border-blue/40 bg-ink px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-bright">
                        {{ is_array($capability) ? data_get($capability, 'label', data_get($capability, 'title', '')) : $capability }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
