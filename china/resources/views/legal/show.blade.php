@extends('layouts.app')

@section('content')
    <article class="section-shell relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-pattern-cloud opacity-[0.08]" aria-hidden="true"></div>
        <div class="section-inner relative max-w-3xl">
            <div class="luck-bar mb-4" aria-hidden="true"></div>
            <h1 class="font-display text-3xl font-semibold text-gold sm:text-4xl">{{ $heading }}</h1>
            <p class="mt-3 text-sm text-paper/60">{{ __('legal.stub_notice') }}</p>
            <div class="mt-8 space-y-4 text-base leading-relaxed text-paper/80">
                @foreach ($paragraphs as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </article>
    @include('sections.footer', ['section' => config('site.fallback.'.app()->getLocale().'.footer', []), 'settings' => config('site.settings')])
@endsection
