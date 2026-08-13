@php
    $brand = $section['brand'] ?? 'GAMIMED';
    $headline = $section['headline'] ?? '';
    $sub = $section['subheadline'] ?? '';
    $ctaPrimary = $section['cta_primary'] ?? '';
    $ctaSecondary = $section['cta_secondary'] ?? '';
    $image = $section['image'] ?? '/images/hero-arab.jpg';
    $whitepaperUrl = $section['whitepaper_url'] ?? null;
    $whitepaperEnabled = (bool) data_get($settings ?? config('site.settings'), 'feature_flags.whitepaper_download', true);
    $whitepaperAvailable = $whitepaperEnabled && \App\Support\PublicDownload::isAvailable(is_string($whitepaperUrl) ? $whitepaperUrl : null);
@endphp

<section class="relative isolate min-h-[100svh] overflow-hidden" aria-label="{{ $brand }}">
    <div class="absolute inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-ink via-emerald-deep to-ink"></div>
        <div
            class="absolute inset-0 opacity-30 mix-blend-soft-light"
            style="background-image: radial-gradient(ellipse at 20% 20%, rgba(212,175,55,0.35), transparent 55%), radial-gradient(ellipse at 80% 70%, rgba(16,185,129,0.25), transparent 50%);"
        ></div>
        <div class="absolute inset-0 bg-pattern-geom opacity-[0.18] mix-blend-soft-light" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-pattern-mashrabiya opacity-[0.10] mix-blend-soft-light" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-pattern-blockchain opacity-[0.12] mix-blend-screen" aria-hidden="true"></div>
        <img
            src="{{ $image }}"
            alt=""
            class="absolute inset-0 h-full w-full object-cover opacity-30"
            onerror="this.style.display='none'"
        >
        <div class="pointer-events-none absolute end-[-6%] top-[16%] h-72 w-72 ornament-calligraphy opacity-20 mix-blend-soft-light sm:h-96 sm:w-96" aria-hidden="true"></div>
        <div class="hero-scrim absolute inset-0" aria-hidden="true"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[100svh] max-w-6xl flex-col justify-end px-4 pb-16 pt-28 sm:px-6 sm:pb-24">
        <p class="hero-type animate-rise font-display text-3xl font-semibold tracking-[0.12em] text-gold sm:text-5xl">
            {{ $brand }}
        </p>
        <h1 class="hero-type animate-rise-delay mt-4 max-w-3xl font-display text-3xl font-semibold leading-tight text-sand sm:text-5xl">
            {{ $headline }}
        </h1>
        <p class="hero-type animate-rise-delay-2 mt-4 max-w-xl text-base text-sand sm:text-lg">
            {{ $sub }}
        </p>
        <div class="animate-rise-delay-2 mt-8 flex flex-wrap items-center gap-3">
            @if ($ctaPrimary)
                <a
                    href="#calculator"
                    class="inline-flex items-center bg-gold px-5 py-3 text-sm font-semibold text-ink transition hover:bg-gold-bright"
                    data-track="cta_click"
                    data-track-meta='{"target":"calculator"}'
                >
                    {{ $ctaPrimary }}
                </a>
            @endif
            @if ($ctaSecondary)
                <a
                    href="#contact"
                    class="inline-flex items-center border border-emerald px-5 py-3 text-sm font-semibold text-sand transition hover:border-gold hover:text-gold"
                    data-track="cta_click"
                    data-track-meta='{"target":"contact"}'
                >
                    {{ $ctaSecondary }}
                </a>
            @endif
            @if ($whitepaperAvailable)
                <a
                    href="{{ $whitepaperUrl }}"
                    class="hero-type inline-flex items-center text-sm font-semibold text-sand underline-offset-4 transition hover:text-gold hover:underline"
                    data-track="whitepaper_download"
                >
                    {{ __('ui.whitepaper') }}
                </a>
            @endif
        </div>
    </div>
</section>
