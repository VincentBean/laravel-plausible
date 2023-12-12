<?php

namespace VincentBean\Plausible\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            \VincentBean\Plausible\LaravelPlausibleServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('plausible.tracking_domain', 'plausible-tracking-domain.test');
        $app['config']->set('plausible.plausible_domain', 'http://plausible-domain.test');
    }
}
