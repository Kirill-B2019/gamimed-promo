@php($items = $section['items'] ?? [])

<section id="partners" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-lattice opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <ul class="mt-10 flex flex-wrap items-center gap-8">
                @foreach ($items as $item)
                    <li class="flex min-h-[3rem] items-center border border-gold/20 bg-ink px-4 py-3">
                        @if (is_array($item) && data_get($item, 'logo'))
                            <img
                                src="{{ $item['logo'] }}"
                                alt="{{ data_get($item, 'name', '') }}"
                                class="max-h-10 w-auto opacity-80 grayscale transition hover:opacity-100 hover:grayscale-0"
                                loading="lazy"
                            >
                        @else
                            <span class="text-sm font-semibold uppercase tracking-[0.16em] text-paper/70">
                                {{ is_array($item) ? data_get($item, 'name', '') : $item }}
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
