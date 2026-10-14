@extends('layouts.adherent')
@section('title', 'Ma cotisation')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Ma cotisation</h1>
    <p class="text-sm text-gray-500">Statut et historique de vos paiements</p>
@endsection

@section('content')

@php
    $statutColor = match($adherent->statut_cotisation) {
        'a_jour'     => ['bg' => '#E6F2EC', 'border' => '#1F7A50', 'text' => '#143F2C'],
        'en_attente' => ['bg' => '#FFFBEB', 'border' => '#F59E0B', 'text' => '#713F12'],
        'expire'     => ['bg' => '#FEF2F2', 'border' => '#C53030', 'text' => '#7F1D1D'],
        default      => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151'],
    };
@endphp

{{-- Statut actuel --}}
<div class="mb-6 p-6 rounded border-l-4" style="background: {{ $statutColor['bg'] }}; border-color: {{ $statutColor['border'] }};">
    <div class="flex items-start justify-between gap-4 flex-wrap">
        <div>
            <div class="text-xs uppercase tracking-wider opacity-70 mb-1" style="color: {{ $statutColor['text'] }};">
                Statut actuel
            </div>
            <div class="font-display text-2xl" style="color: {{ $statutColor['text'] }};">
                {{ match($adherent->statut_cotisation) {
                    'a_jour'     => 'À jour',
                    'en_attente' => 'En attente',
                    'expire'     => 'Expirée',
                    default      => $adherent->statut_cotisation,
                } }}
            </div>

            @if($prochaineEcheance)
                <p class="text-sm opacity-80 mt-2" style="color: {{ $statutColor['text'] }};">
                    Prochaine échéance : <strong>{{ $prochaineEcheance->translatedFormat('d F Y') }}</strong>
                    @if($adherent->jours_avant_expiration !== null && $adherent->jours_avant_expiration > 0)
                        <span class="opacity-75">(dans {{ $adherent->jours_avant_expiration }} jours)</span>
                    @endif
                </p>
            @endif
        </div>

        <div class="text-right">
            <div class="text-xs uppercase tracking-wider opacity-70 mb-1" style="color: {{ $statutColor['text'] }};">
                Montant annuel
            </div>
            <div class="font-display text-2xl" style="color: {{ $statutColor['text'] }};">
                {{ number_format($adherent->cotisation_montant, 0, ',', ' ') }} FCFA
            </div>
        </div>
    </div>
</div>

{{-- Total payé --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Total payé</div>
        <div class="text-2xl font-display text-[#10263B]">
            {{ number_format($totalPaye, 0, ',', ' ') }} FCFA
        </div>
    </div>
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Nombre de paiements</div>
        <div class="text-2xl font-display text-[#10263B]">{{ $paiements->count() }}</div>
    </div>
    <div class="bg-white p-5 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Membre depuis</div>
        <div class="text-2xl font-display text-[#10263B]">
            {{ $adherent->date_adhesion?->translatedFormat('Y') ?? '—' }}
        </div>
    </div>
</div>

{{-- Historique --}}
<div class="bg-white rounded border">
    <div class="p-5 border-b">
        <h2 class="font-display text-lg text-[#10263B]">Historique des paiements</h2>
    </div>

    @if($paiements->count() > 0)
        <div class="divide-y">
            @foreach($paiements as $paiement)
                <div class="p-5 flex items-center justify-between gap-4 flex-wrap">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="font-mono text-xs text-gray-500">{{ $paiement->reference }}</span>
                            <span class="badge badge-green">{{ $paiement->statut_label }}</span>
                        </div>
                        <div class="font-medium text-[#10263B] mt-1.5">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $paiement->mode_paiement_label }}
                            @if($paiement->paid_at)
                                · {{ $paiement->paid_at->translatedFormat('d F Y') }}
                            @endif
                        </div>
                    </div>

                    @if($paiement->statut === 'valide')
                        <a href="{{ route('adherent.cotisation.recu', $paiement->id) }}"
                           class="px-4 py-2 border rounded text-sm font-medium text-[#10263B] hover:border-[#10263B] transition flex-shrink-0">
                            Télécharger le reçu ↓
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="p-10 text-center">
            <p class="text-gray-400 text-sm">Aucun paiement enregistré pour le moment.</p>
            <p class="text-xs text-gray-400 mt-2">Contactez un responsable ALIDSC pour finaliser votre cotisation.</p>
        </div>
    @endif
</div>

{{-- Aide --}}
<div class="mt-6 p-5 rounded" style="background: var(--paper-tint); border-left: 3px solid #0B6FB8;">
    <p class="text-sm text-gray-700">
        Une question sur votre cotisation ?
        <a href="{{ route('contact') }}" class="text-[#0B6FB8] font-medium">Contactez-nous</a>
        ou
        <a href="https://wa.me/237696506940" target="_blank" class="text-[#0B6FB8] font-medium">écrivez-nous sur WhatsApp</a>.
    </p>
</div>

@endsection
