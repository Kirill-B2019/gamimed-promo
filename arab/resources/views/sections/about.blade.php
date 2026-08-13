@php
    $highlights = $section['highlights'] ?? [];
@endphp

<section id="about" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-geom opacity-[0.08]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($highlights))
            <ul class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ($highlights as $item)
                    <li class="luxury-card border-t-2 border-gold pt-4">
                        <h3 class="font-semibold text-ink">{{ data_get($item, 'title', '') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink/75">{{ data_get($item, 'body', '') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
