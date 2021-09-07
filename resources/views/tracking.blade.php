@if (config('laravel-plausible.tracking_domain', null))
<script defer data-domain="{{ config('laravel-plausible.tracking_domain') }}" src="{{ config('laravel-plausible.plausible_domain') }}/js/plausible.js"></script>
@endif
