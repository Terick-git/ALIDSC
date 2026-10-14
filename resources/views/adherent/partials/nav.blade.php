@php
    $closeOnClick = $closeOnClick ?? false;
    $clickAttr = $closeOnClick ? 'x-on:click="open = false"' : '';
@endphp

<p class="text-xs uppercase tracking-wider text-white/40 mb-2 px-3">Mon espace</p>
<nav class="flex flex-col gap-1 text-sm mb-6">
    <a href="{{ route('adherent.dashboard') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.dashboard') ? 'active' : '' }}">
        Tableau de bord
    </a>
        <a href="{{ route('adherent.profile.edit') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.profile.*') ? 'active' : '' }}">
        Mon profil
    </a>
    <a href="{{ route('adherent.cotisation') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.cotisation*') ? 'active' : '' }}">
        Ma cotisation
    </a>
    <a href="{{ route('adherent.documents') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.documents*') ? 'active' : '' }}">
        Documents
    </a>
    <a href="{{ route('adherent.actualites') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.actualites*') ? 'active' : '' }}">
        Actualités internes
    </a>
</nav>

<p class="text-xs uppercase tracking-wider text-white/40 mb-2 px-3">Site public</p>
<nav class="flex flex-col gap-1 text-sm">
    <a href="{{ route('home') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium">
        ← Retour au site
    </a>
</nav>
