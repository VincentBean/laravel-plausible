<?php

namespace VincentBean\Plausible\Tests\Unit;

use Illuminate\Support\Facades\Route;
use VincentBean\Plausible\Middleware\TrackPlausiblePageviews;
use VincentBean\Plausible\Tests\TestCase;
use VincentBean\Plausible\Facades\PlausibleEvent;

class TrackPlausiblePageviewsTest extends TestCase
{
    public function test_it_sends_event(): void
    {
        PlausibleEvent::shouldReceive('fire')
            ->once();

        Route::middleware(TrackPlausiblePageviews::class)
            ->get('/', fn () => 'Hello World');

        $this->get('/');
    }

    public function test_it_does_not_send_event_without_tracking_domain(): void
    {
        config()->set('plausible.tracking_domain', null);

        PlausibleEvent::spy()
            ->shouldNotReceive('fire');

        Route::middleware(TrackPlausiblePageviews::class)
            ->get('/', fn () => 'Hello World');

        $this->get('/');
    }
}
