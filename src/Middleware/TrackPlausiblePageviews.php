<?php

namespace VincentBean\Plausible\Middleware;

use Closure;
use Illuminate\Http\Request;
use VincentBean\Plausible\Events\PlausibleEvent;

class TrackPlausiblePageviews
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (config('plausible.tracking_domain') !== null) {
            PlausibleEvent::fire('pageview');
        }

        return $next($request);
    }
}
