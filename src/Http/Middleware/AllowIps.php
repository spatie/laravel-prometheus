<?php

namespace Spatie\Prometheus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class AllowIps
{
    public function handle(Request $request, Closure $next)
    {
        $allowedIps = config('prometheus.allowed_ips', []);

        if (! count($allowedIps)) {
            return $next($request);
        }

        if (IpUtils::checkIp($request->ip(), $allowedIps)) {
            return $next($request);
        }

        abort(403);
    }
}
