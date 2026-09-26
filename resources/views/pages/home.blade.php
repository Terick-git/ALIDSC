@extends('layouts.app')

@section('title', 'Accueil')
@section('description', 'L\'ALIDSC lutte contre le décrochage scolaire dans l\'Extrême-Nord du Cameroun. Adhérez, faites un don, agissez à nos côtés.')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     1. HERO
     ═══════════════════════════════════════════════════════ --}}
<section class="hero">
    <div class="frame">
        <p class="hero-eyebrow meta">Maroua · Extrême-Nord · Depuis 2022</p>

        <h1 class="hero-title">
            <span class="line line-blue">Étudiez.</span>
            <span class="line line-green">Rêvez.</span>
            <span class="line line-marine">Réussissez.</span>
        </h1>

        <p class="hero-lede">
            L'ALIDSC agit au Cameroun pour prévenir le décrochage scolaire et
            accompagner chaque enfant vers la réussite, en lien étroit avec
            les familles, les écoles et les quartiers de Maroua.
        </p>

        <div class="hero-actions">
            <a href="{{ route('adherer') }}" class="btn btn-outline">Devenir adhérent</a>
            <a href="{{ route('don') }}" class="btn btn-green">Faire un don</a>
        </div>

        <div class="hero-foot">
            <span>Siège à Maroua</span>
            <span>Loi N° 90/053</span>
            <span>Association à but non lucratif</span>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     2. MISSION + 3 OBJECTIFS
     ═══════════════════════════════════════════════════════ --}}
<section class="section section-tint">
    <div class="frame">
        <div class="section-head">
            <span class="meta">Notre mission</span>
            <h2 class="display-2">Rendre l'école possible, pour chaque enfant.</h2>
            <p class="para-lg">
                Nous identifions les enfants en risque de décrochage, nous levons les
                obstacles matériels et familiaux, et nous les accompagnons jusqu'à
                la reprise ou la poursuite de leur scolarité.
            </p>
        </div>

        <div class="flat-grid">
            <div>
                <span class="flat-num">01 — Prévenir</span>
                <h3>Lutter contre le décrochage</h3>
                <p>Identifier les enfants en difficulté, dialoguer avec les familles, lever les freins matériels avant qu'il ne soit trop tard.</p>
            </div>
            <div>
                <span class="flat-num">02 — Valoriser</span>
                <h3>Promouvoir l'excellence</h3>
                <p>Célébrer les réussites, mettre en lumière les parcours exemplaires, créer des modèles pour toute une génération.</p>
            </div>
            <div>
                <span class="flat-num">03 — Ouvrir</span>
                <h3>Susciter des vocations</h3>
                <p>Connecter les jeunes à des professionnels, révéler des passions, montrer que tous les chemins sont possibles.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     3. ACTIONS
     ═══════════════════════════════════════════════════════ --}}
<section class="section">
    <div class="frame">
        <div class="section-head">
            <span class="meta">Nos actions</span>
            <h2 class="display-2">Trois manières d'agir, une seule ambition.</h2>
        </div>

        <div class="flat-grid">
            <div>
                <span class="flat-num">Interventions scolaires</span>
                <h3>Causeries éducatives</h3>
                <p>Nous allons à la rencontre des élèves, des parents et des chefs de quartier pour prévenir le décrochage en amont.</p>
            </div>
            <div>
                <span class="flat-num">Semaines d'impact</span>
                <h3>Kits et accompagnement</h3>
                <p>Distribution de kits scolaires et suivi individualisé pour les enfants dont les familles ne peuvent assumer la rentrée.</p>
            </div>
            <div>
                <span class="flat-num">Études & publications</span>
                <h3>Documentation du terrain</h3>
                <p>Nous documentons nos interventions pour alimenter le débat public et améliorer nos propres pratiques.</p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <a href="{{ route('actions') }}" class="link-arrow">
                Voir toutes nos actions
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     4. DEVENIR ADHÉRENT
     ═══════════════════════════════════════════════════════ --}}
<section class="section section-tint">
    <div class="frame">
        <div class="section-head">
            <span class="meta">Devenir adhérent</span>
            <h2 class="display-2">Rejoignez le mouvement.</h2>
            <p class="para-lg">
                Deux catégories pour s'engager à nos côtés, selon le temps et
                l'attention que vous souhaitez consacrer à la cause.
            </p>
        </div>

        <div class="flat-grid" style="grid-template-columns: 1fr 1fr; border-top: 1px solid var(--line);">
            <div style="border-right: 1px solid var(--line);">
                <span class="flat-num">Membre simple</span>
                <h3>Pour soutenir la cause</h3>
                <p>Intérêt marqué pour l'éducation. Accès aux informations, participation aux activités publiques. Cotisation annuelle.</p>
            </div>
            <div>
                <span class="flat-num">Membre actif</span>
                <h3>Pour s'impliquer</h3>
                <p>Participation régulière aux activités, droit de vote en Assemblée Générale, accès complet à l'espace adhérent.</p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <a href="{{ route('adherer') }}" class="btn btn-primary">Remplir le formulaire d'adhésion</a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     5. BANDEAU DON
     ═══════════════════════════════════════════════════════ --}}
<section class="don-banner">
    <div class="frame-tight">
        <h2>5 000 FCFA équipent un enfant pour toute une année scolaire.</h2>
        <p>Cahiers, stylos, règle, gomme, cartable. Un geste simple, un impact mesurable.</p>
        <a href="{{ route('don') }}" class="btn btn-green">Faire un don</a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     6. DERNIÈRES ACTUALITÉS
     ═══════════════════════════════════════════════════════ --}}
<section class="section">
    <div class="frame">
        <div class="section-head" style="display: flex; justify-content: space-between; align-items: flex-end; max-width: none; gap: 2rem; flex-wrap: wrap;">
            <div>
                <span class="meta">Le journal</span>
                <h2 class="display-2" style="margin-bottom: 0;">Nouvelles du terrain.</h2>
            </div>
            <a href="{{ route('actualites') }}" class="link-arrow" style="margin-bottom: 0.5rem;">
                Toutes les actualités
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        @if($actualites->count() > 0)
            <div class="article-list" style="margin-top: 2rem;">
                @foreach($actualites as $actu)
                    <a href="{{ route('actualites.show', $actu->slug) }}" class="article-item">
                        <div class="article-date">
                            {{ ($actu->publie_le ?? $actu->created_at)->translatedFormat('d M Y') }}
                        </div>
                        <div>
                            <h3 class="article-title">{{ $actu->titre }}</h3>
                            <p class="article-excerpt">{{ $actu->extrait }}</p>
                        </div>
                        <div class="article-image">
                            @if($actu->image)
                                <img src="{{ asset('storage/'.$actu->image) }}" alt="{{ $actu->titre }}" loading="lazy">
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="alert alert-info" style="margin-top: 2rem;">
                Les premières actualités seront publiées très bientôt. Revenez nous voir.
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     7. CTA FINALE
     ═══════════════════════════════════════════════════════ --}}
<section class="cta-final">
    <div class="frame-narrow">
        <h2>Une question, un projet, une envie d'agir ?</h2>
        <p>Écrivez-nous. Nous répondons à chaque message.</p>
        <a href="{{ route('contact') }}" class="btn btn-outline">Nous contacter</a>
    </div>
</section>

@endsection

@push('json-ld')
    <x-json-ld :data="\App\Support\Seo::websiteJsonLd()" />
@endpush