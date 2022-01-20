<?php

namespace VincentBean\LaravelPlausible\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use VincentBean\LaravelPlausible\Middleware\TrackPlausiblePageviews;
use VincentBean\LaravelPlausible\Tests\TestCase;

class TrackPlausiblePageviewsTest extends TestCase
{
    use WithFaker;

    public function testMiddleware()
    {
        $post_url = static::PLAUSIBLE_DOMAIN . '/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ]);

        $request = new \Illuminate\Http\Request();
        $middleware = new TrackPlausiblePageviews();
        $middleware->handle($request, function () use ($post_url) {
            Http::assertSent(function (\Illuminate\Http\Client\Request $request) use ($post_url) {
                return $request->hasHeader('X-Forwarded-For') &&
                    $request->hasHeader('user-agent') &&
                    $request->url() === $post_url &&
                    $request['name'] === 'pageview' &&
                    $request['domain'] === static::PLAUSIBLE_TRACKING_DOMAIN &&
                    $request['url'] === url()->current() &&
                    $request['props'] === json_encode([]);
            });
        });
    }
}
