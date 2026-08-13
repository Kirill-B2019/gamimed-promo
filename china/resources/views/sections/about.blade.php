@php
    $highlights = $section['highlights'] ?? [];
@endphp

<section id="about" class="section-shell relative overflow-hidden bg-sand">
    <div class="pointer-events-none absolute inset-0 bg-pattern-cloud opacity-[0.14]" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-lattice opacity-[0.08]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($highlights))
            <ul class="mt-10 grid gap-6 sm:grid-cols-3">
                @foreach ($highlights as $item)
                    <li class="surface-card">
                        <span class="mb-4 block h-px w-10 bg-gradient-to-r from-red to-gold" aria-hidden="true"></span>
                        <h3 class="font-semibold text-paper">{{ data_get($item, 'title', '') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-paper/75">{{ data_get($item, 'body', '') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
