@php
    $title = $title ?? ($section['title'] ?? '');
    $body = $body ?? ($section['body'] ?? ($section['lead'] ?? ''));
@endphp

@if ($title)
    <span class="section-kicker" aria-hidden="true"></span>
    <h2 class="section-title">{{ $title }}</h2>
@endif
@if ($body)
    <p class="section-lead">{{ $body }}</p>
@endif
