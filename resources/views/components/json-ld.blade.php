@props(['data'])

@if(!empty($data))
    {{-- JSON_HEX_TAG / HEX_AMP / HEX_APOS / HEX_QUOT empêchent toute
         injection via une chaîne de type "</script>..." dans les données. --}}
    <script type="application/ld+json" nonce="{{ $cspNonce ?? '' }}">{!! json_encode(
        $data,
        JSON_UNESCAPED_UNICODE
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ) !!}</script>
@endif