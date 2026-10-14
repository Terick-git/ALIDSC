@extends('layouts.adherent')
@section('title', 'Tableau de bord')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Bonjour, {{ $adherent->prenom }}.</h1>
    <p class="text-sm text-gray-500">Bienvenue dans votre espace adhérent</p>
@endsection

@section('content')

{{-- ═══ Bandeau cotisation ═══ --}}
@php
    $cotColor = match($cotisation['statut']) {
        'a_jour'     => ['bg' => '#E6F2EC', 'border' => '#1F7A50', 'text' => '#143F2C', 'icon' => '✓'],
        'en_attente' => ['bg' => '#FFFBEB', 'border' => '#F59E0B', 'text' => '#713F12', 'icon' => '⏱'],
        'expire'     => ['bg' => '#FEF2F2', 'border' => '#C53030', 'text' => '#7F1D1D', 'icon' => '!'],
        default      => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'icon' => '·'],
    };
@endphp

<div class="mb-6 p-5 rounded border-l-4 flex items-start justify-between gap-4 flex-wrap"
     style="background: {{ $cotColor['bg'] }}; border-color: {{ $cotColor['border'] }}; color: {{ $cotColor['text'] }};">
    <div class="flex items-start gap-3">
        <span style="font-size: 1.5rem; line-height: 1;">{{ $cotColor['icon'] }}</span>
        <div>
            <div class="font-display text-lg" style="color: {{ $cotColor['text'] }};">
                Cotisation : {{ $cotisation['label'] }}
            </div>
            @if($cotisation['statut'] === 'a_jour' && $cotisation['jours_restants'] !== null)
                <p class="text-sm opacity-80 mt-1">
                    Prochaine échéance dans <strong>{{ $cotisation['jours_restants'] }} jours</strong>.
                </p>
            @elseif($cotisation['statut'] === 'expire')
                <p class="text-sm opacity-80 mt-1">Votre cotisation doit être renouvelée pour continuer à accéder à l'espace.</p>
            @elseif($cotisation['statut'] === 'en_attente')
                <p class="text-sm opacity-80 mt-1">Un responsable ALIDSC vous contactera pour finaliser votre paiement.</p>
            @endif
        </div>
    </div>
    <a href="{{ route('adherent.cotisation') }}"
       class="px-4 py-2 bg-white border rounded text-sm font-medium flex-shrink-0"
       style="color: {{ $cotColor['text'] }}; border-color: {{ $cotColor['border'] }};">
        Voir le détail →
    </a>
</div>

{{-- ═══ Cartes statut ═══ --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Catégorie</div>
        <div class="text-lg font-display text-[#10263B]">{{ $adherent->categorie_label }}</div>
    </div>
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Statut</div>
        <div class="text-lg font-display text-[#10263B]">{{ $adherent->statut_demande_label }}</div>
    </div>
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Membre depuis</div>
        <div class="text-lg font-display text-[#10263B]">
            {{ $adherent->date_adhesion?->translatedFormat('M Y') ?? '—' }}
        </div>
    </div>
</div>

{{-- ═══ Contenu en 2 colonnes ═══ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Colonne gauche : actualités + documents --}}
    <div class="space-y-6">

        {{-- Actualités internes --}}
        <div class="bg-white rounded border">
            <div class="p-5 border-b flex justify-between items-center">
                <h2 class="font-display text-lg text-[#10263B]">Actualités internes</h2>
                <a href="{{ route('adherent.actualites') }}" class="text-sm text-[#0B6FB8] hover:underline">Voir tout →</a>
            </div>
            <div class="divide-y">
                @forelse($actualites as $actu)
                    <a href="{{ route('adherent.actualites.show', $actu) }}" class="block p-4 hover:bg-gray-50">
                        <div class="text-xs uppercase tracking-wider text-[#0B6FB8] mb-1">
                            {{ $actu->publie_le?->translatedFormat('d M Y') ?? $actu->created_at->translatedFormat('d M Y') }}
                        </div>
                        <div class="font-medium text-sm text-[#10263B]">{{ $actu->titre }}</div>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($actu->extrait, 100) }}</p>
                    </a>
                @empty
                    <p class="p-6 text-sm text-gray-400 text-center">Aucune actualité interne pour le moment.</p>
                @endforelse
            </div>
        </div>

        {{-- Documents récents --}}
        <div class="bg-white rounded border">
            <div class="p-5 border-b flex justify-between items-center">
                <h2 class="font-display text-lg text-[#10263B]">Documents récents</h2>
                <a href="{{ route('adherent.documents') }}" class="text-sm text-[#0B6FB8] hover:underline">Voir tout →</a>
            </div>
            <div class="divide-y">
                @forelse($documents as $doc)
                    <a href="{{ route('adherent.documents.download', $doc) }}" class="flex items-center justify-between p-4 hover:bg-gray-50">
                        <div class="min-w-0">
                            <div class="font-medium text-sm text-[#10263B] truncate">{{ $doc->titre }}</div>
                            <div class="text-xs text-gray-500">{{ $doc->categorie_label }}</div>
                        </div>
                        <span class="text-xs text-[#0B6FB8] font-medium flex-shrink-0 ml-2">↓</span>
                    </a>
                @empty
                    <p class="p-6 text-sm text-gray-400 text-center">Aucun document disponible.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Colonne droite : événements + actions rapides --}}
    <div class="space-y-6">

        {{-- Prochains événements --}}
        <div class="bg-white rounded border">
            <div class="p-5 border-b flex justify-between items-center">
                <h2 class="font-display text-lg text-[#10263B]">Prochains événements</h2>
                <a href="{{ route('evenements') }}" class="text-sm text-[#0B6FB8] hover:underline">Voir tout →</a>
            </div>
            <div class="divide-y">
                @forelse($evenements as $evt)
                    <div class="p-4">
                        <div class="text-xs uppercase tracking-wider text-[#0B6FB8] mb-1">
                            {{ $evt->date_debut->translatedFormat('d M Y · H\hi') }}
                        </div>
                        <div class="font-medium text-sm text-[#10263B]">{{ $evt->titre }}</div>
                        <div class="text-xs text-gray-500 mt-1">📍 {{ $evt->lieu }}</div>
                    </div>
                @empty
                    <p class="p-6 text-sm text-gray-400 text-center">Aucun événement à venir.</p>
                @endforelse
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="bg-white rounded border">
            <div class="p-5 border-b">
                <h2 class="font-display text-lg text-[#10263B]">Actions rapides</h2>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('adherent.profile.edit') }}"
                   class="flex items-center gap-3 p-3 rounded border hover:border-[#10263B] transition text-sm">
                    <span class="text-[#0B6FB8]">👤</span>
                    Modifier mon profil
                </a>
                <a href="{{ route('adherent.cotisation') }}"
                   class="flex items-center gap-3 p-3 rounded border hover:border-[#10263B] transition text-sm">
                    <span class="text-[#0B6FB8]">💰</span>
                    Ma cotisation
                </a>
                <a href="{{ route('contact') }}"
                   class="flex items-center gap-3 p-3 rounded border hover:border-[#10263B] transition text-sm">
                    <span class="text-[#0B6FB8]">✉️</span>
                    Contacter l'ALIDSC
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
