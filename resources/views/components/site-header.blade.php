@php
    $navItems = [
        ['route' => 'a-propos',   'label' => 'Qui sommes-nous'],
        ['route' => 'actions',    'label' => 'Nos actions'],
        ['route' => 'impact',     'label' => 'Impact'],
        ['route' => 'actualites', 'label' => 'Actualités'],
        ['route' => 'contact',    'label' => 'Contact'],
    ];
@endphp

<header class="top" id="siteHeader">
    <a href="{{ route('home') }}" class="top-brand" aria-label="ALIDSC — Accueil">
        <img src="{{ asset('images/logo-alidsc.jpg') }}"
             alt=""
             class="top-brand-mark"
             width="44" height="44">
        <span>ALIDSC</span>
    </a>

    <nav class="top-nav" aria-label="Navigation principale">
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
               @if(request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*')) aria-current="page" @endif>
                {{ $item['label'] }}
            </a>
        @endforeach
        <a href="{{ route('adherent.login') }}" class="top-nav-secondary">Espace adhérent</a>
    </nav>

    <a href="{{ route('don') }}" class="top-cta-don">Faire un don</a>

    <button class="top-mobile-toggle" id="mobileToggle" type="button"
            aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobilePanel">
        <span></span>
    </button>
</header>
