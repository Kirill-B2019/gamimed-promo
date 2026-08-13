<?php

namespace App\Support;

class SeoMeta
{
    /**
     * @param  array<string, mixed>  $routeParams
     * @return array{
     *     title: string,
     *     description: string,
     *     canonical: string,
     *     image: string,
     *     locale: string,
     *     og_locale: string,
     *     default_locale: string,
     *     alternates: array<string, string>
     * }
     */
    public static function make(
        string $title,
        string $description,
        string $routeName,
        array $routeParams = [],
        ?string $image = null,
    ): array {
        $locales = config('site.locales', ['en']);
        $locale = app()->getLocale();
        $alternates = [];

        foreach ($locales as $alt) {
            $alternates[$alt] = route($routeName, array_merge($routeParams, ['locale' => $alt]));
        }

        $image = $image ?: (string) config('site.seo.og_image', '/images/og.jpg');
        if ($image !== '' && ! str_starts_with($image, 'http')) {
            $image = url($image);
        }

        return [
            'title' => $title,
            'description' => self::description($description),
            'canonical' => $alternates[$locale] ?? url()->current(),
            'image' => $image,
            'locale' => $locale,
            'og_locale' => self::ogLocale($locale),
            'default_locale' => (string) config('site.default_locale', 'en'),
            'alternates' => $alternates,
        ];
    }

    public static function description(string $text): string
    {
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags($text)) ?? '');

        return mb_strlen($plain) > 160 ? mb_substr($plain, 0, 157).'…' : $plain;
    }

    public static function ogLocale(string $locale): string
    {
        return match ($locale) {
            'ar' => 'ar_AR',
            'zh_CN' => 'zh_CN',
            default => 'en_US',
        };
    }

    public static function hreflang(string $locale): string
    {
        return str_replace('_', '-', $locale);
    }
}
