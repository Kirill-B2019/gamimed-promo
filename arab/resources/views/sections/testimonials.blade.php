@php($items = $section['items'] ?? [])

<section id="testimonials" class="section-shell bg-sand-deep">
    <div class="section-inner">
        @include('sections._header', ['section' => $section])

        @if (count($items))
            <ul class="mt-10 grid gap-6 sm:grid-cols-2">
                @foreach ($items as $item)
                    <li class="luxury-card border-s-4 border-gold p-6">
                        <blockquote class="text-ink/85 leading-relaxed">
                            “{{ data_get($item, 'quote', '') }}”
                        </blockquote>
                        <footer class="mt-4">
                            <p class="text-sm font-semibold text-emerald">{{ data_get($item, 'name', data_get($item, 'author', '')) }}</p>
                            @if (data_get($item, 'role'))
                                <p class="text-xs text-ink/60">{{ $item['role'] }}</p>
                            @endif
                        </footer>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
