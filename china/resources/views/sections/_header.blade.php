@php
    $title = $title ?? ($section['title'] ?? '');
    $body = $body ?? ($section['body'] ?? ($section['lead'] ?? ''));
@endphp

@if ($title)
    <div class="luck-bar mb-4" aria-hidden="true"></div>
    <h2 class="section-title">{{ $title }}</h2>
@endif
@if ($body)
    <p class="section-lead">{{ $body }}</p>
@endif
