<?php

namespace VincentBean\LaravelPlausible\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use VincentBean\LaravelPlausible\PlausibleEvent;
use VincentBean\LaravelPlausible\Tests\TestCase;

class PlausibleEventTest extends TestCase
{
    use WithFaker;

    public function testFireCustomUrl()
    {
        $post_url = static::PLAUSIBLE_DOMAIN . '/api/event';

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
                $request['domain'] === static::PLAUSIBLE_TRACKING_DOMAIN &&
                $request['url'] === $url &&
                $request['props'] === json_encode($props);
        });
    }

    public function testFireCurrentUrl()
    {
        $post_url = static::PLAUSIBLE_DOMAIN . '/api/event';

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
                $request['domain'] === static::PLAUSIBLE_TRACKING_DOMAIN &&
                $request['url'] === url()->current() &&
                $request['props'] === json_encode($props);
        });
    }

    public function testCustomUserFingerprint()
    {
        $post_url = static::PLAUSIBLE_DOMAIN . '/api/event';

        Http::fake([
            $post_url => Http::response('{}', Response::HTTP_ACCEPTED),
        ]);

        $name = $this->faker->word();
        $props = [$this->faker->word() => $this->faker->word()];

        PlausibleEvent::fire($name, $props, headers: [
            'X-Forwarded-For' => $ipv = $this->faker->ipv4(),
            'user-agent' => $userAgent = $this->faker->userAgent(),
        ]);

        Http::assertSent(function (Request $request) use ($post_url, $name, $props, $ipv, $userAgent) {
            return $request->header('X-Forwarded-For')[0] === $ipv &&
                $request->header('user-agent')[0] === $userAgent &&
                $request->url() === $post_url &&
                $request['name'] === $name &&
                $request['domain'] === static::PLAUSIBLE_TRACKING_DOMAIN &&
                $request['url'] === url()->current() &&
                $request['props'] === json_encode($props);
        });
    }
}
