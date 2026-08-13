<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.seo')
    @include('partials.analytics')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|roboto:400,500,700|noto-sans-sc:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-ink font-sans text-paper antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-50 focus:bg-gold focus:px-3 focus:py-2">
        {{ __('ui.skip_to_content') }}
    </a>

    <header class="@yield('header_class', 'relative z-20 bg-ink')">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6">
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="font-display text-xl font-semibold tracking-[0.22em] text-paper sm:text-2xl">
                GAMIMED
            </a>
            <div class="flex items-center gap-4">
                <livewire:locale-switcher />
            </div>
        </div>
        <div class="h-px bg-gradient-to-r from-red via-gold to-blue" aria-hidden="true"></div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>
