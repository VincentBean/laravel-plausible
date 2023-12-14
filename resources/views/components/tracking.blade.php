@isset ($trackingDomain)
    <script defer {{ $attributes->merge(['data-domain' => $trackingDomain, 'src' => $src])->except('extensions') }}></script>
@endisset
