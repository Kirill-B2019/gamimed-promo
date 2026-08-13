@php
    $principles = $section['principles'] ?? [];
@endphp

<section id="sharia" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-geom opacity-[0.06]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-mashrabiya opacity-[0.08]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-calligraphy opacity-[0.07]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($principles))
            <ul class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ($principles as $principle)
                    <li class="luxury-card">
                        <h3 class="font-semibold text-ink">{{ data_get($principle, 'title', '') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink/75">{{ data_get($principle, 'body', '') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
