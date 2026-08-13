<?php

namespace App\Http\Controllers;

use App\Services\HubClient;
use App\Support\SeoMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, HubClient $hub): View
    {
        $content = $hub->content();

        $hub->trackEvent('page_view', [
            'path' => $request->getPathInfo(),
            'meta' => ['source' => $content['source'] ?? null],
        ]);

        $sections = $content['sections'] ?? [];
        $hero = $sections['hero'] ?? [];
        $brand = data_get($hero, 'brand', 'GAMIMED');
        $headline = (string) data_get($hero, 'headline', '');
        $title = $headline !== '' ? $brand.' — '.$headline : __('seo.title');

        return view('home', [
            'content' => $content,
            'sections' => $sections,
            'settings' => $content['site']['settings'] ?? config('site.settings'),
            'seo' => SeoMeta::make(
                $title,
                (string) data_get($hero, 'subheadline', __('seo.description')),
                'home',
                [],
                data_get($hero, 'image'),
            ),
        ]);
    }

    public function switchLocale(string $locale): RedirectResponse
    {
        $supported = config('site.locales', ['zh_CN', 'en']);

        if (! in_array($locale, $supported, true)) {
            $locale = config('site.default_locale', 'zh_CN');
        }

        session(['locale' => $locale]);

        return redirect()
            ->route('home', ['locale' => $locale])
            ->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
