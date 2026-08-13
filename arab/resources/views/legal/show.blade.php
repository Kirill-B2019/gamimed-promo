@extends('layouts.app')

@section('content')
    <article class="section-shell">
        <div class="section-inner max-w-3xl">
            <h1 class="font-display text-3xl font-semibold text-gold sm:text-4xl">{{ $heading }}</h1>
            <p class="mt-3 text-sm text-ink/60">{{ __('legal.stub_notice') }}</p>
            <div class="mt-8 space-y-4 text-base leading-relaxed text-ink/80">
                @foreach ($paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </article>
    @include('sections.footer', ['section' => config('site.fallback.'.app()->getLocale().'.footer', []), 'settings' => config('site.settings')])
@endsection
