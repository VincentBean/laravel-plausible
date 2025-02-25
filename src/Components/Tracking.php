<?php

namespace VincentBean\Plausible\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tracking extends Component
{
    public string $src;

    public function __construct(
        public ?string $trackingDomain = null,
        public ?string $plausibleDomain = null,
        public array|string|null $extensions = null
    ) {
        $this->trackingDomain ??= config('plausible.tracking_domain');
        $this->plausibleDomain ??= config('plausible.plausible_domain');

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

    public function render(): View
    {
        /** @var view-string $view */
        $view = 'plausible::components.tracking';

        return view($view, [
            'trackingDomain' => $this->trackingDomain,
            'plausibleDomain' => $this->plausibleDomain,
            'extensions' => $this->extensions,
            'src' => $this->src,
        ]);
    }
}
