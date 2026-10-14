@props([
    'icon'        => 'book',
    'title'       => 'Rien à afficher pour le moment',
    'text'        => 'Revenez bientôt.',
    'actionUrl'   => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state__icon">
        <x-icon :name="$icon" :size="32" />
    </div>

    <h3 class="empty-state__title">{{ $title }}</h3>
    <p class="empty-state__text">{{ $text }}</p>

    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="link-arrow">
            {{ $actionLabel }}
            <x-icon name="arrow-right" :size="16" />
        </a>
    @endif
</div>