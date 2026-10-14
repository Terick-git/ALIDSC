@php
    $mobileMode = $mobileMode ?? false;
    $clickAttr = $mobileMode ? 'x-on:click="mobileOpen = false"' : '';
@endphp

<nav class="flex flex-col gap-1 text-sm">
    <a href="{{ route('dashboard') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        Tableau de bord
    </a>

    <a href="{{ route('admin.actualites.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.actualites.*') ? 'active' : '' }}">
        Actualités
    </a>

    <a href="{{ route('admin.actions.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.actions.*') ? 'active' : '' }}">
        Actions
    </a>

    <a href="{{ route('admin.partenaires.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.partenaires.*') ? 'active' : '' }}">
        Partenaires
    </a>

    <a href="{{ route('admin.adherents.index') }}"
       {!! $clickAttr !!}
       class="flex items-center justify-between px-3 py-2 rounded font-medium {{ request()->routeIs('admin.adherents.*') ? 'active' : '' }}">
        <span>Adhérents</span>
        <template x-if="counts.adherents_en_attente > 0">
            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold"
                  style="background: #F59E0B; color: white;"
                  x-text="counts.adherents_en_attente"></span>
        </template>
    </a>

    <a href="{{ route('admin.documents.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
        Documents
    </a>

    <a href="{{ route('dashboard') }}#dons"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium">
        Dons
    </a>

    <a href="{{ route('admin.temoignages.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.temoignages.*') ? 'active' : '' }}">
        Témoignages
    </a>

    <a href="{{ route('admin.evenements.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.evenements.*') ? 'active' : '' }}">
        Événements
    </a>

    <a href="{{ route('admin.albums.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.albums.*') ? 'active' : '' }}">
        Albums
    </a>

    <a href="{{ route('admin.ressources.index') }}"
       {!! $clickAttr !!}
       class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.ressources.*') ? 'active' : '' }}">
        Ressources
    </a>

    <a href="{{ route('admin.messages.index') }}"
       {!! $clickAttr !!}
       class="flex items-center justify-between px-3 py-2 rounded font-medium {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
        <span>Messages</span>
        <template x-if="counts.messages_non_lus > 0">
            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold"
                  style="background: #DC2626; color: white;"
                  x-text="counts.messages_non_lus"></span>
        </template>
    </a>
</nav>