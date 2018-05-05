<?php

namespace WoganMay\GDPRBlocker;

use Closure;

class GDPRMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Create IP Set
        $rangesFile = base_path("vendor/woganmay/gdpr-blackhole/eu-ranges.txt");
        $ipRanges = explode("\n", file_get_contents($rangesFile));
        $IPSet = new \IPSet\IPSet($ipRanges);

        if ($IPSet->match($_SERVER['REMOTE_ADDR']))
        {
            abort(451, 'Intercepted by GDPR Blocker');
        }
        else
        {
            return $next($request);
        }

    }
}