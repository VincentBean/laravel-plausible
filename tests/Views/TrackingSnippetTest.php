<?php

namespace VincentBean\Plausible\Tests\Views;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use VincentBean\Plausible\Components\Tracking;
use VincentBean\Plausible\Tests\TestCase;

class TrackingSnippetTest extends TestCase
{
    use InteractsWithViews;

    public function test_render_snippet(): void
    {
        $tracking_domain = config('plausible.tracking_domain');
        $domain = config('plausible.plausible_domain');

        $this->view('plausible::tracking', ['trackingdomain' => $tracking_domain])
            ->assertSee("<script defer data-domain=\"$tracking_domain\" src=\"$domain/js/script.js\"></script>", false)
            ->assertSee('<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>', false);
    }

    public function test_render_component(): void
    {
        $tracking_domain = config('plausible.tracking_domain');
        $domain = config('plausible.plausible_domain');

        $this->component(Tracking::class)
            ->assertSee("<script defer data-domain=\"$tracking_domain\" src=\"$domain/js/script.js\"></script>", false);

        $this->component(Tracking::class, ['tracking-do,ain' => 'analytics.test.com', 'extensions' => 'hash,outbound-link, revenue'])
            ->assertSee('<script defer data-domain="plausible-tracking-domain.test" src="http://plausible-domain.test/js/script.hash.outbound-link.revenue.js"></script>', false);
    }
}
