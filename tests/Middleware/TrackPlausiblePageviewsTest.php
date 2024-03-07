<?php

namespace VincentBean\Plausible\Tests\Unit;

use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use VincentBean\Plausible\Middleware\TrackPlausiblePageviews;
use VincentBean\Plausible\Tests\TestCase;

class TrackPlausiblePageviewsTest extends TestCase
{
    public function test_it_sends_event(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([$post_url => Http::response()]);

        Route::middleware(TrackPlausiblePageviews::class)
            ->get('/', fn () => 'Hello World');

        $this->get('/');

        Http::assertSent(function (Request $request) use ($post_url) {
            return $request->hasHeader('X-Forwarded-For') &&
                $request->hasHeader('user-agent') &&
                $request->url() === $post_url &&
                $request['name'] === 'pageview' &&
                $request['domain'] === 'plausible-tracking-domain.test';
        });
    }

    public function test_it_handles_event(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ])->preventStrayRequests();

        $request = new \Illuminate\Http\Request();

        $middleware = new TrackPlausiblePageviews();

        $middleware->handle($request, function () use ($post_url) {
            Http::assertSent(function (Request $request) use ($post_url) {
                return $request->hasHeader('X-Forwarded-For') &&
                    $request->hasHeader('user-agent') &&
                    $request->url() === $post_url &&
                    $request['name'] === 'pageview' &&
                    $request['domain'] === 'plausible-tracking-domain.test';
            });
        });
    }

    public function test_it_does_not_send_event_without_tracking_domain(): void
    {
        config()->set('plausible.tracking_domain', null);

        Http::fake()->preventStrayRequests();

        $request = new \Illuminate\Http\Request();
        $middleware = new TrackPlausiblePageviews();
        $middleware->handle($request, function () {
            Http::assertNothingSent();
        });
    }
}
