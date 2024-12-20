<?php

namespace VincentBean\Plausible\Tests\Events;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use VincentBean\Plausible\Facades\PlausibleEvent;
use VincentBean\Plausible\Tests\TestCase;

class PlausibleEventTest extends TestCase
{
    use WithFaker;

    public function test_fire_custom_url(): void
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

    public function test_fire_current_url(): void
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

    public function test_custom_user_fingerprint(): void
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

    public function test_fire_handles_exception_gracefully(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_INTERNAL_SERVER_ERROR),
        ]);

        $url = $this->faker->url();
        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        $result = PlausibleEvent::fire($name, $props, ['url' => $url]);

        $this->assertFalse($result, 'The fire method should return false on exception');
    }

    public function test_fire_handles_host_down_exception_gracefully(): void
    {
        $post_url = 'http://plausible-domain.test/api/event';

        Http::fake([
            $post_url => fn () => throw new \Illuminate\Http\Client\RequestException(
                new \Illuminate\Http\Client\Response(new \GuzzleHttp\Psr7\Response(0))
            ),
        ]);

        $url = $this->faker->url();
        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        $result = PlausibleEvent::fire($name, $props, ['url' => $url]);

        $this->assertFalse($result, 'The fire method should return false when the host is down');
    }
}
