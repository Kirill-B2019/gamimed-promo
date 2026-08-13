@extends('layouts.app')

@section('header_class', 'absolute inset-x-0 top-0 z-20 bg-gradient-to-b from-ink via-ink/80 to-transparent')

@section('content')
    @include('sections.hero', ['section' => $sections['hero'] ?? [], 'settings' => $settings])
    @include('sections.about', ['section' => $sections['about'] ?? []])
    @include('sections.tokenomics', ['section' => $sections['tokenomics'] ?? [], 'settings' => $settings])
    @include('sections.roadmap', ['section' => $sections['roadmap'] ?? []])
    @include('sections.team', ['section' => $sections['team'] ?? []])
    @include('sections.partners', ['section' => $sections['partners'] ?? []])
    @include('sections.security', ['section' => $sections['security'] ?? []])
    @include('sections.technology', ['section' => $sections['technology'] ?? []])
    @include('sections.testimonials', ['section' => $sections['testimonials'] ?? []])
    @include('sections.faq', ['section' => $sections['faq'] ?? []])
    @include('sections.contact', ['section' => $sections['contact'] ?? [], 'settings' => $settings])
    @include('sections.footer', ['section' => $sections['footer'] ?? [], 'settings' => $settings])
@endsection
