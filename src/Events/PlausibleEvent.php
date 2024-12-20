<?php

namespace VincentBean\Plausible\Events;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class PlausibleEvent
{
    /**
     * Fire a Plausible event.
     */
    public static function fire(string $name, array $props = [], array $args = [], array $headers = []): bool
    {
        try {
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
                        'referrer' => Arr::get($headers, 'Referrer', request()->header('Referer')),
                        'props' => json_encode($props),
                    ])
                )
                ->successful();
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            \Log::error('PlausibleEvent fire failed: '.$e->getMessage());

            // Return false to gracefully handle the failure
            return false;
        }
    }
}
