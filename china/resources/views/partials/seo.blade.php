@php
    use App\Support\SeoMeta;

    $seo = $seo ?? [
        'title' => __('seo.title'),
        'description' => __('seo.description'),
        'canonical' => url()->current(),
        'image' => url(config('site.seo.og_image', '/images/og.jpg')),
        'locale' => app()->getLocale(),
        'og_locale' => SeoMeta::ogLocale(app()->getLocale()),
        'default_locale' => config('site.default_locale'),
        'alternates' => [],
    ];
@endphp
<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">
@foreach ($seo['alternates'] as $locale => $url)
    <link rel="alternate" hreflang="{{ SeoMeta::hreflang($locale) }}" href="{{ $url }}">
@endforeach
@if (! empty($seo['alternates'][$seo['default_locale']]))
    <link rel="alternate" hreflang="x-default" href="{{ $seo['alternates'][$seo['default_locale']] }}">
@endif
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name', 'GAMIMED') }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonical'] }}">
<meta property="og:image" content="{{ $seo['image'] }}">
<meta property="og:locale" content="{{ $seo['og_locale'] }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] }}">
<meta name="twitter:description" content="{{ $seo['description'] }}">
<meta name="twitter:image" content="{{ $seo['image'] }}">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'GAMIMED',
    'url' => $seo['canonical'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
