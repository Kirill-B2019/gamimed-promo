@php($items = $section['items'] ?? [])

<section id="roadmap" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-tech opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <ol class="relative mt-10 space-y-8 border-s border-gold/35 ps-6 sm:ps-8">
                @foreach ($items as $item)
                    <li class="relative">
                        <span class="absolute -start-[5px] top-1.5 h-2.5 w-2.5 bg-gold ring-4 ring-sand" aria-hidden="true"></span>
                        @if (data_get($item, 'period'))
                            <span class="inline-block bg-blue/20 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-blue-bright">
                                {{ $item['period'] }}
                            </span>
                        @endif
                        <h3 class="mt-2 font-semibold text-paper">{{ data_get($item, 'title', '') }}</h3>
                        @if (data_get($item, 'body'))
                            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-paper/75">{{ $item['body'] }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</section>
