<?php

namespace VincentBean\Plausible\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool fire(string $name, array $props = [], array $args = [], array $headers = []): bool
 *
 * @see \VincentBean\Plausible\Events\PlausibleEvent
 */
class PlausibleEvent extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \VincentBean\Plausible\Events\PlausibleEvent::class;
    }
}
