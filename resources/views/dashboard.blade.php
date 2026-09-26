@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Tableau de bord</h1>
    <p class="text-sm text-gray-500">Vue d'ensemble de l'activité ALIDSC</p>
@endsection

@section('content')

{{-- Bloc stats principales --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Adhérents</div>
        <div class="text-3xl font-display text-[#10263B]">{{ $stats['adherents']['total'] }}</div>
        <div class="text-xs text-gray-500 mt-1">
            <span class="text-green-600 font-medium">{{ $stats['adherents']['a_jour'] }} actifs</span>
            · <span class="text-amber-600 font-medium">{{ $stats['adherents']['en_attente'] }} en attente</span>
        </div>
    </div>

    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Recettes cotisations</div>
        <div class="text-3xl font-display text-[#10263B]">
            {{ number_format($stats['adherents']['recettes'], 0, ',', ' ') }}
        </div>
        <div class="text-xs text-gray-500 mt-1">FCFA encaissés</div>
    </div>

    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Messages</div>
        <div class="text-3xl font-display text-[#10263B]">{{ $stats['messages']['total'] }}</div>
        <div class="text-xs text-gray-500 mt-1">
            @if($stats['messages']['non_lus'] > 0)
                <span class="text-red-600 font-medium">{{ $stats['messages']['non_lus'] }} non lu(s)</span>
            @else
                Tous lus
            @endif
        </div>
    </div>

    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Contenus</div>
        <div class="text-3xl font-display text-[#10263B]">
            {{ $stats['actualites']['total'] + $stats['actions']['total'] }}
        </div>
        <div class="text-xs text-gray-500 mt-1">
            {{ $stats['actualites']['publiees'] }} articles publiés
        </div>
    </div>
</div>

{{-- Deux colonnes --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Derniers adhérents --}}
    <div class="bg-white rounded border">
        <div class="p-5 border-b flex justify-between items-center">
            <h2 class="font-display text-lg text-[#10263B]">Dernières demandes</h2>
            <a href="{{ route('admin.adherents.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout →</a>
        </div>
        <div class="divide-y">
            @forelse($derniers_adherents as $a)
                <a href="{{ route('admin.adherents.show', $a) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                    <div>
                        <div class="font-medium text-sm">{{ $a->nom_complet }}</div>
                        <div class="text-xs text-gray-500">{{ $a->email }}</div>
                    </div>
                    <div class="text-right">
                        <span class="badge @if($a->statut_demande==='active') badge-green @elseif($a->statut_demande==='en_attente') badge-amber @elseif($a->statut_demande==='refuse') badge-red @else badge @endif">
                            {{ $a->statut_demande_label }}
                        </span>
                        <div class="text-xs text-gray-400 mt-1">{{ $a->created_at->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <p class="p-6 text-sm text-gray-400 text-center">Aucune demande pour le moment.</p>
            @endforelse
        </div>
    </div>

    {{-- Derniers messages --}}
    <div class="bg-white rounded border">
        <div class="p-5 border-b flex justify-between items-center">
            <h2 class="font-display text-lg text-[#10263B]">Derniers messages</h2>
            <a href="{{ route('admin.messages.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout →</a>
        </div>
        <div class="divide-y">
            @forelse($derniers_messages as $m)
                <a href="{{ route('admin.messages.show', $m) }}" class="block p-4 hover:bg-gray-50 @if(!$m->lu) bg-amber-50/30 @endif">
                    <div class="flex justify-between items-baseline">
                        <div class="font-medium text-sm truncate">{{ $m->sujet }}</div>
                        <div class="text-xs text-gray-400 ml-2 flex-shrink-0">{{ $m->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-xs text-gray-500 truncate mt-1">
                        {{ $m->nom }} — {{ $m->email }}
                    </div>
                </a>
            @empty
                <p class="p-6 text-sm text-gray-400 text-center">Aucun message.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Raccourcis --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
    <a href="{{ route('admin.actualites.create') }}" class="bg-white p-5 rounded border hover:border-[#10263B] transition text-center">
        <div class="font-display text-lg text-[#10263B]">+ Actualité</div>
    </a>
    <a href="{{ route('admin.actions.create') }}" class="bg-white p-5 rounded border hover:border-[#10263B] transition text-center">
        <div class="font-display text-lg text-[#10263B]">+ Action</div>
    </a>
    <a href="{{ route('admin.evenements.create') }}" class="bg-white p-5 rounded border hover:border-[#10263B] transition text-center">
        <div class="font-display text-lg text-[#10263B]">+ Événement</div>
    </a>
    <a href="{{ route('admin.albums.create') }}" class="bg-white p-5 rounded border hover:border-[#10263B] transition text-center">
        <div class="font-display text-lg text-[#10263B]">+ Album</div>
    </a>
</div>

@endsection