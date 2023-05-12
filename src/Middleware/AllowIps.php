<?php

namespace Spatie\Prometheus\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class AllowIps
{
    public function handle(Request $request, Closure $next)
    {
        $allowedIps = config('horizon-exporter.ip_whitelist');

        if (IpUtils::checkIp($request->ip(), $allowedIps)) {
            return $next($request);
        }

        abort(403);
    }
}
