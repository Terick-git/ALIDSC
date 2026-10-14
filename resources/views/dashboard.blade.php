@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Tableau de bord</h1>
    <p class="text-sm text-gray-500">Vue d'ensemble de l'activité ALIDSC</p>
@endsection

@section('content')

{{-- ═══════════════════════════════════════════════════════
     LIGNE 1 — Chiffres principaux
     ═══════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    {{-- Adhérents --}}
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Adhérents</div>
        <div class="text-3xl font-display text-[#10263B]">{{ $stats['adherents']['total'] }}</div>
        <div class="text-xs text-gray-500 mt-1">
            <span class="text-green-600 font-medium">{{ $stats['adherents']['a_jour'] }} actifs</span>
            @if($stats['adherents']['en_attente'] > 0)
                · <span class="text-amber-600 font-medium">{{ $stats['adherents']['en_attente'] }} en attente</span>
            @endif
        </div>
    </div>

    {{-- Recettes cotisations --}}
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Cotisations</div>
        <div class="text-3xl font-display text-[#10263B]">
            {{ number_format($stats['adherents']['recettes'], 0, ',', ' ') }}
        </div>
        <div class="text-xs text-gray-500 mt-1">FCFA encaissés</div>
    </div>

    {{-- Messages --}}
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

    {{-- Contenus --}}
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

{{-- ═══════════════════════════════════════════════════════
     LIGNE 2 — Dons
     ═══════════════════════════════════════════════════════ --}}
<div class="bg-white p-6 rounded border mb-6" style="border-left: 4px solid #1F7A50;">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#E6F2EC; color:#1F7A50;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"/>
                </svg>
            </span>
            <h2 class="font-display text-lg text-[#10263B]">Dons</h2>
        </div>
        @if($stats['dons']['total'] > 0)
            <a href="{{ route('don') }}" target="_blank" class="text-xs text-gray-500 hover:text-gray-900">
                Voir la page publique →
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Ce mois-ci</div>
            <div class="text-2xl font-display text-[#10263B]">
                {{ number_format($stats['dons']['montant_mois'], 0, ',', ' ') }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                FCFA · {{ $stats['dons']['count_mois'] }} don{{ $stats['dons']['count_mois'] > 1 ? 's' : '' }}
            </div>
        </div>

        <div>
            <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Total collecté</div>
            <div class="text-2xl font-display text-[#10263B]">
                {{ number_format($stats['dons']['montant_total'], 0, ',', ' ') }}
            </div>
            <div class="text-xs text-gray-500 mt-1">FCFA depuis le lancement</div>
        </div>

        <div>
            <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Dons payés</div>
            <div class="text-2xl font-display text-[#10263B]">{{ $stats['dons']['payes'] }}</div>
            <div class="text-xs text-gray-500 mt-1">
                sur {{ $stats['dons']['total'] }} au total
            </div>
        </div>

        <div>
            <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">En attente</div>
            <div class="text-2xl font-display {{ $stats['dons']['en_attente'] > 0 ? 'text-amber-600' : 'text-[#10263B]' }}">
                {{ $stats['dons']['en_attente'] }}
            </div>
            <div class="text-xs text-gray-500 mt-1">
                {{ $stats['dons']['en_attente'] > 0 ? 'à vérifier' : 'aucun' }}
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     LIGNE 3 — Derniers dons
     ═══════════════════════════════════════════════════════ --}}
@if($derniers_dons->count())
<div class="bg-white rounded border mb-6">
    <div class="p-5 border-b flex justify-between items-center">
        <h2 class="font-display text-lg text-[#10263B]">Derniers dons</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Référence</th>
                    <th class="px-4 py-3">Donateur</th>
                    <th class="px-4 py-3">Montant</th>
                    <th class="px-4 py-3">Moyen</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($derniers_dons as $don)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs text-gray-600">
                            {{ $don->reference }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $don->nom }}</div>
                            <div class="text-xs text-gray-500">{{ $don->email }}</div>
                        </td>
                        <td class="px-4 py-3 font-semibold text-[#10263B]">
                            {{ $don->montant_formate }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $don->moyen_label }}
                        </td>
                        <td class="px-4 py-3">
                            @if($don->statut === 'paye')
                                <span class="badge badge-green">Payé</span>
                            @elseif($don->statut === 'en_attente')
                                <span class="badge badge-amber">En attente</span>
                            @elseif($don->statut === 'echoue')
                                <span class="badge badge-red">Échoué</span>
                            @else
                                <span class="badge">{{ $don->statut_label }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">
                            {{ $don->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════
     LIGNE 4 — Adhérents + Messages (2 colonnes)
     ═══════════════════════════════════════════════════════ --}}
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
                    <div class="min-w-0">
                        <div class="font-medium text-sm truncate">{{ $a->nom_complet }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $a->email }}</div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
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
                    <div class="flex justify-between items-baseline gap-3">
                        <div class="font-medium text-sm truncate">{{ $m->sujet }}</div>
                        <div class="text-xs text-gray-400 flex-shrink-0">{{ $m->created_at->diffForHumans() }}</div>
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

{{-- ═══════════════════════════════════════════════════════
     LIGNE 5 — Raccourcis
     ═══════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
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