@if (config('laravel-plausible.tracking_domain', null))
<script defer {{ $attributes->merge(['data-domain' => $trackingDomain, 'src' => $src])->except('extensions') }}></script>
@endif
