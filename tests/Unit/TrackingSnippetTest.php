<?php

namespace VincentBean\LaravelPlausible\Tests\Unit;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use VincentBean\LaravelPlausible\Tests\TestCase;

class TrackingSnippetTest extends TestCase
{
    use InteractsWithViews;

    public function testRenderSnippet()
    {
        $tracking_domain = config('laravel-plausible.tracking_domain');
        $domain = config('laravel-plausible.plausible_domain');

        $this->view('plausible::tracking')
            ->assertSee("<script defer data-domain=\"$tracking_domain\" src=\"$domain/js/script.js\"></script>", false)
            ->assertSee("<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>", false);
    }

    public function testRenderComponent()
    {
        $tracking_domain = config('laravel-plausible.tracking_domain');
        $domain = config('laravel-plausible.plausible_domain');

        $this->blade('<x-plausible::tracking />')
            ->assertSee("<script defer data-domain=\"$tracking_domain\" src=\"$domain/js/script.js\"></script>", false);

        $this->blade('<x-plausible::tracking tracking-domain="analytics.test.com" extensions="hash,outbound-links, revenue" />')
            ->assertSee("<script defer data-domain=\"analytics.test.com\" src=\"$domain/js/script.hash.outbound-links.revenue.js\"></script>", false);

        $this->blade('<x-plausible::tracking data-domain="analytics.test.com" extensions="hash.outbound-links" />')
            ->assertSee("<script defer data-domain=\"analytics.test.com\" src=\"$domain/js/script.hash.outbound-links.js\"></script>", false);
    }
}
