<?php

namespace VincentBean\Plausible;

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
            __DIR__.'/../config/plausible.php',
            'plausible'
        );
    }

    protected function bootConfig(): self
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/plausible.php' => config_path('plausible.php'),
            ], 'config');
        }

        return $this;
    }

    protected function bootViews(): self
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'plausible');

        Blade::componentNamespace('VincentBean\\Plausible\\Components', 'plausible');

        return $this;
    }
}
