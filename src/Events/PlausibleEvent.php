<?php

namespace VincentBean\Plausible\Events;

use Illuminate\Support\Facades\Http;

class PlausibleEvent
{
    public static function fire(string $name, array $props = [], array $args = [], array $headers = []): bool
    {
        return Http::withHeaders(array_merge([
            'X-Forwarded-For' => request()->ip(),
            'user-agent' => request()->userAgent(),
        ], $headers))
            ->post(
                config('plausible.plausible_domain').'/api/event',
                array_merge($args, [
                    'name' => $name,
                    'domain' => config('plausible.tracking_domain'),
                    'url' => $args['url'] ?? url()->current(),
                    'props' => json_encode($props),
                ])
            )
            ->successful();
    }
}
