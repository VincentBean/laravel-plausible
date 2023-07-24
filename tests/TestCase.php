<?php

namespace VincentBean\LaravelPlausible\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected const PLAUSIBLE_TRACKING_DOMAIN = 'plausible-tracking-domain.test';
    protected const PLAUSIBLE_DOMAIN = 'https://plausible-domain.test';

    protected function getPackageProviders($app): array
    {
        return [
            'VincentBean\LaravelPlausible\LaravelPlausibleServiceProvider',
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('laravel-plausible.tracking_domain', static::PLAUSIBLE_TRACKING_DOMAIN);
        $app['config']->set('laravel-plausible.plausible_domain', static::PLAUSIBLE_DOMAIN);
    }
}
