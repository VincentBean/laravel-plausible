@if (config('plausible.tracking_domain', null))
<script defer data-domain="{{ config('plausible.tracking_domain') }}" src="{{ config('plausible.plausible_domain') }}/js/script.js"></script>
<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>
@endif
