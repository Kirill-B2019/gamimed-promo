<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = $request->user();

        if (! $site instanceof Site || ! $site->isActive()) {
            return response()->json(['message' => 'Site is inactive or unauthorized.'], 403);
        }

        $request->attributes->set('site', $site);

        return $next($request);
    }
}
