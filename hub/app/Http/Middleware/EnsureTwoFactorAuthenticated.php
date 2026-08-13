<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorAuthenticated
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('hub.admin_2fa')) {
            return $next($request);
        }

        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        $path = $request->path();

        if (str_contains($path, 'two-factor') || $request->routeIs('filament.admin.auth.logout')) {
            return $next($request);
        }

        if ($user->two_factor_confirmed_at === null) {
            return redirect('/admin/two-factor-setup');
        }

        if (! $request->session()->get('hub.2fa_passed')) {
            return redirect('/admin/two-factor-challenge');
        }

        return $next($request);
    }
}
