<?php

namespace VincentBean\LaravelPlausible;

use Illuminate\Support\Facades\Http;

class Event
{
    public static function fire(string $name, array $props = [], array $args = []): bool
    {
        return Http::withHeaders([
            'X-Forwarded-For' => request()->ip(),
            'user-agent'      => request()->userAgent(),
        ])
            ->post(
                config('laravel-plausible.plausible_domain').'/api/event',
                array_merge($args, [
                    'name'   => $name,
                    'domain' => config('laravel-plausible.tracking_domain'),
                    'url'    => $args['url'] ?? url()->current(),
                    'props'  => json_encode($props),
                ])
            )
            ->successful();

    }
}
