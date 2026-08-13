@php
    $points = $section['points'] ?? [];
@endphp

<section id="security" class="section-shell bg-sand-deep">
    <div class="section-inner">
        @include('sections._header', ['section' => $section])

        @if (count($points))
            <ul class="mt-8 grid gap-4 sm:grid-cols-2">
                @foreach ($points as $point)
                    <li class="luxury-card flex gap-3 text-ink/80">
                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald" aria-hidden="true"></span>
                        <span>{{ is_array($point) ? data_get($point, 'body', data_get($point, 'title', '')) : $point }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
