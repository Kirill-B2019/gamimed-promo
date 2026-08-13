<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('site.locales', ['zh_CN', 'en']);
        $default = config('site.default_locale', 'zh_CN');

        $locale = $request->route('locale')
            ?? $request->session()->get('locale')
            ?? $request->cookie('locale')
            ?? $default;

        if (! in_array($locale, $supported, true)) {
            $locale = $default;
        }

        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
