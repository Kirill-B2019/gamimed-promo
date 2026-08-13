@php
    $brand = $section['brand'] ?? 'GAMIMED';
    $headline = $section['headline'] ?? '';
    $sub = $section['subheadline'] ?? '';
    $ctaPrimary = $section['cta_primary'] ?? '';
    $ctaSecondary = $section['cta_secondary'] ?? '';
    $image = $section['image'] ?? '/images/hero-china.jpg';
    $whitepaperUrl = $section['whitepaper_url'] ?? null;
    $whitepaperEnabled = (bool) data_get($settings ?? config('site.settings'), 'feature_flags.whitepaper_download', true);
    $whitepaperAvailable = $whitepaperEnabled && \App\Support\PublicDownload::isAvailable(is_string($whitepaperUrl) ? $whitepaperUrl : null);
@endphp

<section class="relative isolate min-h-[100svh] overflow-hidden" aria-label="{{ $brand }}">
    <div class="absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-ink via-blue-deep to-red-deep"></div>
        <div
            class="absolute inset-0 opacity-35 mix-blend-soft-light"
            style="background-image: radial-gradient(ellipse at 82% 28%, rgba(196,30,58,0.35), transparent 50%), radial-gradient(ellipse at 70% 80%, rgba(29,78,216,0.4), transparent 55%);"
        ></div>
        <div class="absolute inset-0 bg-pattern-cloud opacity-[0.12] mix-blend-soft-light" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-pattern-tech opacity-[0.18] mix-blend-screen" aria-hidden="true"></div>
        <img
            src="{{ $image }}"
            alt=""
            class="absolute inset-0 h-full w-full object-cover object-[78%_center] opacity-55"
            onerror="this.style.display='none'"
        >
        <div class="pointer-events-none absolute end-[-8%] top-[8%] h-72 w-72 ornament-phoenix opacity-25 mix-blend-soft-light sm:h-[28rem] sm:w-[28rem]" aria-hidden="true"></div>
        <div class="pointer-events-none absolute end-[8%] bottom-[-10%] h-56 w-56 ornament-dragon opacity-20 mix-blend-soft-light sm:h-80 sm:w-80" aria-hidden="true"></div>
        <div class="absolute inset-0 hero-scan opacity-50" aria-hidden="true"></div>
        <div class="hero-scrim absolute inset-0" aria-hidden="true"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[100svh] max-w-6xl items-center px-4 pt-28 pb-16 sm:px-6">
        <div class="max-w-xl">
            <div class="luck-bar mb-5" aria-hidden="true"></div>
            <p class="hero-type animate-rise font-display text-2xl font-semibold tracking-[0.28em] text-paper sm:text-4xl">
                {{ $brand }}
            </p>
            <h1 class="hero-type animate-rise-delay mt-4 font-display text-3xl font-semibold leading-tight text-paper sm:text-5xl">
                {{ $headline }}
            </h1>
            <p class="hero-type animate-rise-delay-2 mt-4 text-base text-paper sm:text-lg">
                {{ $sub }}
            </p>
            <div class="animate-rise-delay-2 mt-8 flex flex-wrap items-center gap-3">
                @if ($ctaPrimary)
                    <a
                        href="#calculator"
                        class="cta-luck"
                        data-track="cta_click"
                        data-track-meta='{"target":"calculator"}'
                    >
                        {{ $ctaPrimary }}
                    </a>
                @endif
                @if ($ctaSecondary)
                    <a
                        href="#contact"
                        class="inline-flex items-center border border-blue-bright px-5 py-3 text-sm font-semibold text-paper transition hover:border-gold hover:text-gold"
                        data-track="cta_click"
                        data-track-meta='{"target":"contact"}'
                    >
                        {{ $ctaSecondary }}
                    </a>
                @endif
                @if ($whitepaperAvailable)
                    <a
                        href="{{ $whitepaperUrl }}"
                        class="hero-type inline-flex items-center text-sm font-semibold text-paper underline-offset-4 transition hover:text-gold hover:underline"
                        data-track="whitepaper_download"
                    >
                        {{ __('ui.whitepaper') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
