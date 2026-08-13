@php($items = $section['items'] ?? [])

<section id="testimonials" class="section-shell relative overflow-hidden bg-sand-deep">
    <div class="pointer-events-none absolute inset-0 bg-pattern-phoenix opacity-[0.10]" aria-hidden="true"></div>
    <div class="section-inner relative">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <ul class="mt-10 grid gap-6 sm:grid-cols-2">
                @foreach ($items as $item)
                    <li class="surface-card border-s-2 border-s-red p-6">
                        <blockquote class="leading-relaxed text-paper/85">
                            “{{ data_get($item, 'quote', '') }}”
                        </blockquote>
                        <footer class="mt-4">
                            <p class="text-sm font-semibold text-red">{{ data_get($item, 'name', data_get($item, 'author', '')) }}</p>
                            @if (data_get($item, 'role'))
                                <p class="text-xs text-paper/60">{{ $item['role'] }}</p>
                            @endif
                        </footer>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
