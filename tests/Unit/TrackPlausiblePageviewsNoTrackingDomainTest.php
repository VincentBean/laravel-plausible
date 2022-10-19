<?php

namespace VincentBean\LaravelPlausible\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use VincentBean\LaravelPlausible\Middleware\TrackPlausiblePageviews;
use VincentBean\LaravelPlausible\Tests\TestCase;

class TrackPlausiblePageviewsNoTrackingDomainTest extends TestCase
{
    use WithFaker;

    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('laravel-plausible.tracking_domain', null);
    }

    public function testMiddleware()
    {
        Http::fake();

        $request = new \Illuminate\Http\Request();
        $middleware = new TrackPlausiblePageviews();
        $middleware->handle($request, function () {
            Http::assertNothingSent();
        });
    }
}
