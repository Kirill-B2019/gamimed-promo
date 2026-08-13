@php
    $text = filled($disclaimer ?? null) ? $disclaimer : __('legal.risk_disclaimer');
@endphp
@if (filled($text))
    <p class="text-xs leading-relaxed text-sand/55" data-disclaimer="risk">
        {{ $text }}
    </p>
@endif
