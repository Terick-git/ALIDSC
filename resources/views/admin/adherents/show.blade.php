@extends('layouts.admin')
@section('title', $adherent->nom_complet)
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">{{ $adherent->nom_complet }}</h1>
    <p class="text-sm text-gray-500">Demande déposée le {{ $adherent->created_at->format('d/m/Y') }}</p>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-900 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded border">
            <h2 class="font-display text-lg mb-4 text-[#10263B]">Informations</h2>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Email</dt><dd>{{ $adherent->email }}</dd></div>
                <div><dt class="text-gray-500">Téléphone</dt><dd>{{ $adherent->telephone }}</dd></div>
                <div><dt class="text-gray-500">Date de naissance</dt><dd>{{ $adherent->date_naissance?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Ville</dt><dd>{{ $adherent->ville ?? '—' }}</dd></div>
                <div class="col-span-2"><dt class="text-gray-500">Adresse</dt><dd>{{ $adherent->adresse ?? '—' }}</dd></div>
                <div class="col-span-2"><dt class="text-gray-500">Établissement</dt><dd>{{ $adherent->etablissement ?? '—' }}</dd></div>
                @if($adherent->notes)<div class="col-span-2"><dt class="text-gray-500">Motivation</dt><dd class="whitespace-pre-line">{{ $adherent->notes }}</dd></div>@endif
            </dl>
        </div>

        <div class="bg-white p-6 rounded border">
            <h2 class="font-display text-lg mb-4 text-[#10263B]">Actions</h2>
            <div class="flex flex-wrap gap-3">
                @if($adherent->statut_demande === 'en_attente')
                    <form method="POST" action="{{ route('admin.adherents.accepter', $adherent) }}">@csrf
                        <button class="px-4 py-2 bg-green-600 text-white rounded text-sm">Accepter</button>
                    </form>
                    <button type="button" onclick="document.getElementById('refusForm').classList.toggle('hidden')" class="px-4 py-2 bg-red-600 text-white rounded text-sm">Refuser</button>
                @endif

                @if(in_array($adherent->statut_demande, ['acceptee', 'paiement_validate']))
                    <form method="POST" action="{{ route('admin.adherents.activer', $adherent) }}">@csrf
                        <button class="px-4 py-2 bg-[#10263B] text-white rounded text-sm">Activer l'adhésion</button>
                    </form>
                @endif

                @if($adherent->piece_identite)
                    <a href="{{ route('admin.adherents.piece', $adherent) }}" class="px-4 py-2 border rounded text-sm">Télécharger la pièce d'identité</a>
                @endif

                <a href="{{ route('admin.adherents.edit', $adherent) }}" class="px-4 py-2 border rounded text-sm">Modifier</a>
            </div>

            <div id="refusForm" class="hidden mt-4 p-4 bg-red-50 rounded">
                <form method="POST" action="{{ route('admin.adherents.refuser', $adherent) }}">@csrf
                    <label class="block text-sm font-medium mb-2">Motif du refus *</label>
                    <textarea name="motif_refus" rows="3" required class="w-full p-2 border rounded text-sm"></textarea>
                    <button class="mt-2 px-4 py-2 bg-red-600 text-white rounded text-sm">Confirmer le refus</button>
                </form>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded border">
            <h3 class="font-display text-base mb-3 text-[#10263B]">Statut</h3>
            <p class="text-sm"><span class="badge badge-amber">{{ $adherent->statut_demande_label }}</span></p>
            <p class="text-xs text-gray-500 mt-2">Catégorie : {{ $adherent->categorie_label }}</p>
            @if($adherent->motif_refus)
                <p class="text-xs text-red-600 mt-2">Motif : {{ $adherent->motif_refus }}</p>
            @endif
        </div>

        <div class="bg-white p-6 rounded border">
            <h3 class="font-display text-base mb-3 text-[#10263B]">Paiement</h3>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between"><dt class="text-gray-500">Statut</dt><dd>{{ $adherent->statut_cotisation }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Montant</dt><dd>{{ number_format($adherent->cotisation_montant, 0, ',', ' ') }} F</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Dernier paiement</dt><dd>{{ $adherent->date_dernier_paiement?->format('d/m/Y') ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Mode</dt><dd>{{ $adherent->mode_paiement ?? '—' }}</dd></div>
            </dl>

            <form method="POST" action="{{ route('admin.adherents.payer', $adherent) }}" class="mt-4 space-y-2">@csrf
                <select name="mode_paiement" required class="w-full p-2 border rounded text-sm">
                    <option value="mobile_money">Mobile Money</option>
                    <option value="especes">Espèces</option>
                    <option value="virement">Virement</option>
                    <option value="autre">Autre</option>
                </select>
                <input type="number" name="montant" placeholder="Montant (FCFA)" required value="{{ $adherent->cotisation_proposee ?? $adherent->cotisation_montant }}" class="w-full p-2 border rounded text-sm">
                <button class="w-full px-4 py-2 bg-[#0B6FB8] text-white rounded text-sm">Enregistrer le paiement</button>
            </form>
        </div>
    </div>
</div>
@endsection