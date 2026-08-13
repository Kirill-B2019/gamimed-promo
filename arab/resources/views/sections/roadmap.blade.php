@php($items = $section['items'] ?? [])

<section id="roadmap" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-geom opacity-[0.08]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <ol class="relative mt-10 space-y-8 border-s-2 border-gold/40 ps-6 sm:ps-8">
                @foreach ($items as $item)
                    <li class="relative">
                        <span class="absolute -start-[calc(0.75rem+1px)] top-1.5 h-3 w-3 rounded-full bg-gold ring-4 ring-sand"></span>
                        @if (data_get($item, 'period'))
                            <span class="inline-block rounded bg-emerald/10 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-emerald">
                                {{ $item['period'] }}
                            </span>
                        @endif
                        <h3 class="mt-2 font-semibold text-ink">{{ data_get($item, 'title', '') }}</h3>
                        @if (data_get($item, 'body'))
                            <p class="mt-1 max-w-2xl text-sm leading-relaxed text-ink/75">{{ $item['body'] }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</section>
