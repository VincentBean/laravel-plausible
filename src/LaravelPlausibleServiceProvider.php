<?php

namespace VincentBean\LaravelPlausible;

use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPlausibleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this
            ->bootConfig()
            ->bootViews();
    }

    protected function bootConfig(): self
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-plausible.php' => config_path('laravel-plausible.php'),
        ], 'config');

        return $this;
    }

    protected function bootViews(): self
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'plausible');

        return $this;
    }
}
