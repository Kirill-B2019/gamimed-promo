@php
    $copyright = $section['copyright'] ?? 'GAMIMED';
    $links = $section['links'] ?? [];
    $locale = app()->getLocale();
@endphp

<footer class="relative overflow-hidden border-t border-gold/20 bg-ink text-paper/80">
    <div class="pointer-events-none absolute inset-0 bg-pattern-cloud opacity-[0.10] mix-blend-soft-light" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0 bg-pattern-phoenix opacity-[0.10] mix-blend-soft-light" aria-hidden="true"></div>
    <div class="pointer-events-none absolute end-[-6%] bottom-[-12%] h-56 w-56 ornament-dragon opacity-20 mix-blend-soft-light" aria-hidden="true"></div>
    <div class="h-px bg-gradient-to-r from-red via-gold to-blue" aria-hidden="true"></div>
    <div class="relative mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <p class="font-display text-lg font-semibold tracking-[0.18em] text-gold">GAMIMED</p>
            @if (count($links))
                <nav class="flex flex-wrap gap-4 text-sm" aria-label="{{ __('ui.footer_nav') }}">
                    @foreach ($links as $link)
                        <a href="{{ data_get($link, 'url', '#') }}" class="transition hover:text-gold">
                            {{ data_get($link, 'label', '') }}
                        </a>
                    @endforeach
                </nav>
            @endif
            @include('sections._social-links', ['settings' => $settings ?? []])
        </div>
        <div class="mt-6 flex flex-col gap-4 border-t border-paper/10 pt-6 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p>{{ $copyright }}</p>
            <nav class="flex flex-wrap gap-4" aria-label="{{ __('ui.legal_nav') }}">
                <a href="{{ route('legal.terms', ['locale' => $locale]) }}" class="transition hover:text-gold">{{ __('ui.terms') }}</a>
                <a href="{{ route('legal.privacy', ['locale' => $locale]) }}" class="transition hover:text-gold">{{ __('ui.privacy') }}</a>
            </nav>
        </div>
        <div class="mt-6 border-t border-paper/10 pt-6">
            @include('partials.risk-disclaimer', ['disclaimer' => $section['disclaimer'] ?? null])
        </div>
    </div>
</footer>
