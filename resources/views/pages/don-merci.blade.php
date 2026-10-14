@extends('layouts.app')

@section('title', 'Merci pour votre don')
@section('robots', 'noindex, nofollow')
@section('description', 'Merci pour votre générosité — ALIDSC.')

@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-narrow" style="text-align: center;">
        <div style="width: 72px; height: 72px; margin: 0 auto 2rem; border-radius: 50%; background: var(--green-light); display: flex; align-items: center; justify-content: center;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </div>

        <span class="meta" style="color: var(--blue); display: block; margin-bottom: 2rem;">— Don confirmé</span>

        <h1 class="display-2" style="margin-bottom: 2rem;">
            Merci <em>{{ $donation->nom }}.</em>
        </h1>

        <p class="para-lg" style="max-width: 600px; margin: 0 auto;">
            Votre don de <strong>{{ $donation->montant_formate }}</strong>
            permet d'équiper <strong>{{ $donation->kit_equivalent }} {{ $donation->kit_equivalent > 1 ? 'enfants' : 'enfant' }}</strong>
            pour toute une année scolaire à Maroua.
        </p>
    </div>
</section>

<section style="padding: 0 0 8rem;">
    <div class="frame-narrow">
        <div style="background: var(--paper-tint); border: 1px solid var(--line); border-radius: var(--radius); padding: 3rem 2.5rem;">
            <h2 class="display-3" style="font-size: 1.25rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--line);">
                Détails du don
            </h2>

            <dl style="margin: 0;">
                <div style="display: flex; justify-content: space-between; gap: 1.5rem; padding: 1rem 0; border-bottom: 1px solid var(--line-soft);">
                    <dt style="color: var(--text-muted); font-size: 0.9375rem;">Référence</dt>
                    <dd style="font-family: 'JetBrains Mono', monospace; font-size: 0.875rem; color: var(--marine);">{{ $donation->reference }}</dd>
                </div>
                <div style="display: flex; justify-content: space-between; gap: 1.5rem; padding: 1rem 0; border-bottom: 1px solid var(--line-soft);">
                    <dt style="color: var(--text-muted); font-size: 0.9375rem;">Montant</dt>
                    <dd style="font-weight: 600; color: var(--marine);">{{ $donation->montant_formate }}</dd>
                </div>
                <div style="display: flex; justify-content: space-between; gap: 1.5rem; padding: 1rem 0; border-bottom: 1px solid var(--line-soft);">
                    <dt style="color: var(--text-muted); font-size: 0.9375rem;">Moyen</dt>
                    <dd style="font-weight: 600; color: var(--marine);">{{ $donation->moyen_label }}</dd>
                </div>
                <div style="display: flex; justify-content: space-between; gap: 1.5rem; padding: 1rem 0; border-bottom: 1px solid var(--line-soft);">
                    <dt style="color: var(--text-muted); font-size: 0.9375rem;">Statut</dt>
                    <dd>
                        @if($donation->est_paye)
                            <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; background: var(--green-light); color: #143F2C; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em;">
                                ✓ Payé
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; background: #FEF3C7; color: #713F12; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                En attente
                            </span>
                        @endif
                    </dd>
                </div>
                @if($donation->paid_at)
                <div style="display: flex; justify-content: space-between; gap: 1.5rem; padding: 1rem 0;">
                    <dt style="color: var(--text-muted); font-size: 0.9375rem;">Date</dt>
                    <dd style="font-weight: 600; color: var(--marine);">{{ $donation->paid_at->translatedFormat('d F Y à H\hi') }}</dd>
                </div>
                @endif
            </dl>

            @if($donation->est_paye)
                <div style="margin-top: 2.5rem; display: flex; flex-wrap: wrap; gap: 1rem;">
                    {{-- Lien signé (30 jours) : le PDF contient des données personnelles --}}
                    <a href="{{ URL::temporarySignedRoute('don.recu', now()->addDays(30), ['reference' => $donation->reference]) }}"
                       style="display: inline-flex; align-items: center; gap: 0.75rem; padding: 1rem 1.75rem; background: var(--marine); color: white; border-radius: var(--radius); font-size: 0.9375rem; font-weight: 600; text-decoration: none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Télécharger mon reçu
                    </a>
                    <a href="{{ route('home') }}"
                       style="display: inline-flex; align-items: center; padding: 1rem 1.75rem; color: var(--marine); border: 1.5px solid var(--marine); border-radius: var(--radius); font-size: 0.9375rem; font-weight: 600; text-decoration: none;">
                        Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>

        <p style="text-align: center; margin-top: 3rem; font-size: 0.9375rem; color: var(--text-muted);">
            Un email de confirmation a été envoyé à <strong style="color: var(--marine);">{{ $donation->email }}</strong>.
        </p>
    </div>
</section>

@endsection