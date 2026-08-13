@php($items = $section['items'] ?? [])

<section id="faq" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-geom opacity-[0.08]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <div class="mt-8 divide-y divide-ink/10">
                @foreach ($items as $item)
                    <details class="group py-4">
                        <summary class="cursor-pointer list-none font-semibold text-ink marker:content-none [&::-webkit-details-marker]:hidden">
                            <span class="flex items-center justify-between gap-4">
                                {{ data_get($item, 'question', '') }}
                                <span class="text-gold transition group-open:rotate-45" aria-hidden="true">+</span>
                            </span>
                        </summary>
                        <p class="mt-3 max-w-3xl text-ink/75">{{ data_get($item, 'answer', '') }}</p>
                    </details>
                @endforeach
            </div>
        @endif
    </div>
</section>
