<?php

namespace VincentBean\Plausible\Tests\Events;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use VincentBean\Plausible\Facades\PlausibleEvent;
use VincentBean\Plausible\Tests\TestCase;

class PlausibleEventTest extends TestCase
{
    use WithFaker;

    public function testFireCustomUrl(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ]);

        $url = $this->faker->url();
        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        PlausibleEvent::fire($name, $props, ['url' => $url]);

        Http::assertSent(function (Request $request) use ($post_url, $url, $name, $props) {
            return $request->hasHeader('X-Forwarded-For') &&
                $request->hasHeader('user-agent') &&
                $request->url() === $post_url &&
                $request['name'] === $name &&
                $request['domain'] === 'plausible-tracking-domain.test' &&
                $request['url'] === $url &&
                $request['props'] === json_encode($props);
        });
    }

    public function testFireCurrentUrl(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ]);

        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        PlausibleEvent::fire($name, $props);

        Http::assertSent(function (Request $request) use ($post_url, $name, $props) {
            return $request->hasHeader('X-Forwarded-For') &&
                $request->hasHeader('user-agent') &&
                $request->url() === $post_url &&
                $request['name'] === $name &&
                $request['domain'] === 'plausible-tracking-domain.test' &&
                $request['url'] === url()->current() &&
                $request['props'] === json_encode($props);
        });
    }

    public function testCustomUserFingerprint(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ])->preventStrayRequests();

        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        PlausibleEvent::fire($name, $props, headers: [
            'X-Forwarded-For' => $ipv = $this->faker->ipv4(),
            'user-agent' => $userAgent = $this->faker->userAgent(),
            'Referrer' => 'http://localhost',
        ]);

        Http::assertSent(function (Request $request) use ($post_url, $name, $ipv, $userAgent) {
            return $request->header('X-Forwarded-For')[0] === $ipv &&
                $request->header('user-agent')[0] === $userAgent &&
                $request->url() === $post_url &&
                $request['name'] === $name &&
                $request['domain'] === 'plausible-tracking-domain.test' &&
                $request['referrer'] === 'http://localhost';
        });
    }

    public function testFireHandlesExceptionGracefully(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        # Create a mock response to pass to the RequestException
        $mockResponse = new \Illuminate\Http\Client\Response(
            new \GuzzleHttp\Psr7\Response(500, [], 'Simulated server error')
        );

        # Simulate a RequestException with the mock response
        Http::fake([
            $post_url => fn() => throw new \Illuminate\Http\Client\RequestException($mockResponse),
        ]);

        # Spy on the log to capture any logged messages
        \Log::spy();

        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        # Ensure the fire method returns false when an exception occurs
        $result = PlausibleEvent::fire($name, $props);

        $this->assertFalse($result, 'The fire method should return false on exception');

        # Verify that an error was logged
        \Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function ($message) {
                return str_contains($message, 'PlausibleEvent fire failed');
            });
    }

}
