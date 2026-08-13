@php
    $points = $section['points'] ?? [];
@endphp

<section id="security" class="section-shell relative overflow-hidden bg-sand-deep">
    <div class="pointer-events-none absolute inset-0 bg-pattern-tech opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($points))
            <ul class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($points as $point)
                    <li class="surface-card flex gap-3 text-paper/80">
                        <span class="mt-1.5 h-2 w-2 shrink-0 bg-red" aria-hidden="true"></span>
                        <span>{{ is_array($point) ? data_get($point, 'body', data_get($point, 'title', '')) : $point }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
