<?php

namespace VincentBean\LaravelPlausible\Middleware;

use Closure;
use Illuminate\Http\Request;
use VincentBean\LaravelPlausible\PlausibleEvent;

class TrackPlausiblePageviews
{
    public function handle(Request $request, Closure $next)
    {
        if (config('laravel-plausible.tracking_domain') !== null) {
            PlausibleEvent::fire('pageview');
        }

        return $next($request);
    }
}
