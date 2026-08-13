<div class="flex items-center gap-1 text-sm" wire:key="locale-{{ $locale }}">
    @foreach ($locales as $code)
        <a
            href="{{ route('locale.switch', ['locale' => $code]) }}"
            wire:click.prevent="switch('{{ $code }}')"
            @class([
                'px-2 py-1 font-medium transition',
                'text-gold underline underline-offset-4' => $locale === $code,
                'text-paper/70 hover:text-paper' => $locale !== $code,
            ])
            @if ($locale === $code) aria-current="true" @endif
            hreflang="{{ str_replace('_', '-', $code) }}"
        >
            {{ strtoupper($code === 'zh_CN' ? 'ZH' : $code) }}
        </a>
        @if (! $loop->last)
            <span class="text-paper/40" aria-hidden="true">|</span>
        @endif
    @endforeach
</div>
