@props(['label' => null])

<div {{ $attributes->merge(['class' => 'divider']) }} aria-hidden="true">
    <span class="divider__line"></span>
    <span class="divider__mark">
        <span class="divider__dot--sm divider__dot"></span>
        <span class="divider__dot"></span>
        <span class="divider__dot--sm divider__dot"></span>
    </span>
    <span class="divider__line"></span>
</div>
