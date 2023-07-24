<?php

namespace VincentBean\LaravelPlausible;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LaravelPlausibleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this
            ->bootConfig()
            ->bootViews();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/laravel-plausible.php',
            'laravel-plausible'
        );
    }

    protected function bootConfig(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-plausible.php' => config_path('laravel-plausible.php'),
            ], 'config');
        }

        return $this;
    }

    protected function bootViews(): self
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'plausible');

        Blade::componentNamespace('VincentBean\\LaravelPlausible\\Components', 'plausible');

        return $this;
    }
}
