<?php

namespace VincentBean\LaravelPlausible\Middleware;

use Closure;
use Illuminate\Http\Request;
use VincentBean\LaravelPlausible\Event;

class TrackPlausiblePageviews
{
    public function handle(Request $request, Closure $next)
    {
        Event::fire('pageview');

        return $next($request);
    }
}
