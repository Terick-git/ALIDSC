@php
    $navItems = [
        ['route' => 'a-propos',   'label' => 'Qui sommes-nous'],
        ['route' => 'actions',    'label' => 'Nos actions'],
        ['route' => 'impact',     'label' => 'Impact'],
        ['route' => 'actualites', 'label' => 'Actualités'],
        ['route' => 'ressources', 'label' => 'Ressources'],
        ['route' => 'contact',    'label' => 'Contact'],
    ];
@endphp

<div class="mobile-panel" id="mobilePanel">
    <nav aria-label="Navigation mobile">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
        @endforeach
        <a href="{{ route('adherent.login') }}">Espace adhérent</a>
    </nav>

    <a href="{{ route('don') }}" class="mobile-panel-cta">
        Faire un don
    </a>
</div>
