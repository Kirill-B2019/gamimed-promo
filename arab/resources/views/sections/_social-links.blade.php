@php
    $social = collect(data_get($settings, 'social', config('site.settings.social', [])))
        ->filter(fn ($item) => is_array($item) && filled(data_get($item, 'url')))
        ->values();
@endphp

@if ($social->isNotEmpty())
    <nav class="flex flex-wrap items-center gap-3" aria-label="{{ __('ui.social_nav') }}">
        @foreach ($social as $item)
            @php
                $itemKey = strtolower((string) data_get($item, 'key', ''));
                $itemUrl = (string) data_get($item, 'url', '#');
                $itemLabel = (string) data_get($item, 'label', $itemKey);
            @endphp
            <a
                href="{{ $itemUrl }}"
                @if (! str_starts_with($itemUrl, '#'))
                    target="_blank"
                    rel="noopener noreferrer"
                @endif
                class="inline-flex text-sand/80 transition hover:text-gold"
                aria-label="{{ $itemLabel }}"
                data-track="cta_click"
                data-track-meta='{"target":"{{ $itemKey }}"}'
            >
                @include('sections._social-icon', ['key' => $itemKey])
                <span class="sr-only">{{ $itemLabel }}</span>
            </a>
        @endforeach
    </nav>
@endif
