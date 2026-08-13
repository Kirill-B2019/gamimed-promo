<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /track',
            '',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ]);

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap(): Response
    {
        $locales = config('site.locales', ['en']);
        $pages = ['home', 'legal.terms', 'legal.privacy'];
        $urls = [];

        foreach ($pages as $routeName) {
            $alternates = [];

            foreach ($locales as $locale) {
                $alternates[$locale] = route($routeName, ['locale' => $locale]);
            }

            foreach ($locales as $locale) {
                $urls[] = [
                    'loc' => $alternates[$locale],
                    'alternates' => $alternates,
                ];
            }
        }

        return response()
            ->view('seo.sitemap', [
                'urls' => $urls,
                'defaultLocale' => config('site.default_locale'),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
