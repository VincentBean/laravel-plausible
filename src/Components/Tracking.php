<?php

namespace VincentBean\LaravelPlausible\Components;

use Illuminate\View\Component;

class Tracking extends Component
{
    public string $src;

    public function __construct(
        public ?string $trackingDomain = null,
        public ?string $plausibleDomain = null,
        public array|string|null $extensions = null
    ) {
        $this->trackingDomain ??= config('laravel-plausible.tracking_domain');
        $this->plausibleDomain ??= config('laravel-plausible.plausible_domain');

        if (! is_array($this->extensions)) {
            $this->extensions = array_map(
                fn ($i) => trim($i),
                explode(',', (string) $this->extensions)
            );
        }

        $this->src = implode('/', [
            rtrim($this->plausibleDomain, '/'),
            'js',
            implode('.', [
                'script',
                ...array_filter($this->extensions),
                'js',
            ]),
        ]);
    }

    public function render()
    {
        return view('plausible::components.tracking');
    }
}
