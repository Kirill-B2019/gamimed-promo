<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminByIp
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('hub.admin_allowed_ips', []);

        if ($allowed === []) {
            return $next($request);
        }

        $ip = (string) $request->ip();

        if (! IpUtils::checkIp($ip, $allowed)) {
            abort(403, 'Admin access is restricted to the allowlisted network.');
        }

        return $next($request);
    }
}
