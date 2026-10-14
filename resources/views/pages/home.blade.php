@extends('layouts.app')

@section('title', 'Accueil')
@section('description', 'L\'ALIDSC lutte contre le décrochage scolaire dans l\'Extrême-Nord du Cameroun. Adhérez, faites un don, agissez à nos côtés.')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     01 · MASTHEAD
     ═══════════════════════════════════════════════════════ --}}
<section class="masthead">
    <div class="masthead__content">
        <header class="masthead__rail">
            <span>Est. 2022</span>
            <span>Maroua · Extrême-Nord · Cameroun</span>
        </header>

        <h1 class="masthead__title">
            <span class="masthead__line">Étudiez.</span>
            <span class="masthead__line">Rêvez.</span>
            <span class="masthead__line masthead__line--accent">Réussissez.</span>
        </h1>

        <div class="masthead__foot">
            <p class="masthead__lede">
                Nous agissons dans les quartiers de Maroua pour lever les obstacles
                qui poussent les enfants hors de l'école.
            </p>

            <div class="masthead__actions">
                <a href="{{ route('adherer') }}" class="btn btn-primary">
                    Devenir adhérent
                    <x-icon name="arrow-right" :size="16" />
                </a>
                <a href="{{ route('don') }}" class="masthead__ghost">
                    Faire un don
                </a>
            </div>
        </div>
    </div>

    <div class="masthead__figure">
        <img src="{{ asset('images/hero-cameroun.jpg') }}"
             alt="Enfants scolarisés dans l'Extrême-Nord du Cameroun"
             loading="eager">
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     02 · CHIFFRES CLÉS (compteurs animés)
     ═══════════════════════════════════════════════════════ --}}
<section class="ledger">
    <div class="frame">
        <div class="number-row">
            <div class="number-value"><span data-counter="34" data-counter-duration="1600">34</span><span class="plus">+</span></div>
            <div class="number-detail">
                <h3>Élèves accompagnés</h3>
                <p>Des enfants qui auraient décroché sans intervention — aujourd'hui scolarisés ou en cours de raccrochage.</p>
            </div>
        </div>

        <div class="number-row">
            <div class="number-value"><span data-counter="12" data-counter-duration="1400">12</span></div>
            <div class="number-detail">
                <h3>Causeries éducatives</h3>
                <p>Organisées dans les cinq quartiers les plus touchés de Maroua, en lien avec les chefs de quartier et les familles.</p>
            </div>
        </div>

        <div class="number-row">
            <div class="number-value">5000<span class="unit">FCFA</span></div>
            <div class="number-detail">
                <h3>Le coût d'un kit scolaire</h3>
                <p>Cahiers, stylos, règle, gomme, cartable. Assez pour équiper un enfant sur toute une année scolaire.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     03 · NOS ACTIONS SUR LE TERRAIN
     ═══════════════════════════════════════════════════════ --}}
<section class="section--l section--tint">
    <div class="frame">
        <header class="section-head">
            <p class="meta">— Sur le terrain</p>
            <h2 class="section-head__title">
                Ce que nous faisons,<br>
                <em>concrètement.</em>
            </h2>
        </header>

        @if($actions->count() > 0)
            <div class="card-grid">
                @foreach($actions as $action)
                    <a href="{{ route('actions.show', $action->slug) }}" class="card fade">
                        <div class="card__image">
                            <img src="{{ $action->image ? asset('storage/'.$action->image) : asset('images/placeholder-action.jpg') }}"
                                 alt="{{ $action->titre }}"
                                 loading="lazy">
                        </div>
                        <div class="card__body">
                            <span class="card__meta">{{ $action->categorie }}</span>
                            <h3 class="card__title">{{ $action->titre }}</h3>
                            <p class="card__excerpt">{{ $action->extrait }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 3rem;">
                <a href="{{ route('actions') }}" class="link-arrow">
                    Voir toutes nos actions
                    <x-icon name="arrow-right" :size="16" />
                </a>
            </div>
        @else
            <div class="placeholder-grid">
                <div class="placeholder-card">
                    <x-icon name="school" :size="32" />
                    <h3>Bientôt</h3>
                    <p>Nos premières actions terrain seront publiées ici.</p>
                </div>
                <div class="placeholder-card">
                    <x-icon name="kit" :size="32" />
                    <h3>Distribution de kits</h3>
                    <p>Chaque don finance un kit scolaire complet pour un enfant.</p>
                </div>
                <div class="placeholder-card">
                    <x-icon name="users" :size="32" />
                    <h3>Causeries éducatives</h3>
                    <p>Nous dialoguons avec les familles dans les quartiers de Maroua.</p>
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     04 · MANIFESTE
     ═══════════════════════════════════════════════════════ --}}
<section class="statement">
    <div class="frame-tight">
        <p class="meta" style="color: var(--blue); margin-bottom: 2rem;">— Notre conviction</p>
        <p class="statement__quote">
            L'éducation est <em>le levier le plus puissant</em> pour transformer
            une vie. Chaque enfant de l'Extrême-Nord mérite d'aller au bout de la sienne.
        </p>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     05 · TÉMOIGNAGES
     ═══════════════════════════════════════════════════════ --}}
<section class="section--l section--paper">
    <div class="frame">
        <header class="section-head">
            <p class="meta">— Ils nous font confiance</p>
            <h2 class="section-head__title">
                Des visages, <em>des voix.</em>
            </h2>
        </header>

        @if($temoignages->count() > 0)
            <div class="quote-grid">
                @foreach($temoignages as $t)
                    <figure class="quote-card">
                        @if($t->photo)
                            <div class="quote-card__photo">
                                <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->nom }}" loading="lazy">
                            </div>
                        @endif
                        <blockquote class="quote-card__text">« {{ $t->citation }} »</blockquote>
                        <figcaption class="quote-card__attr">
                            <span class="quote-card__name">{{ $t->nom }}</span>
                            <span class="quote-card__role">{{ $t->role }}</span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @else
            <div class="quote-grid">
                <figure class="quote-card quote-card--placeholder">
                    <blockquote class="quote-card__text">« Grâce à l'ALIDSC, ma fille a repris le chemin de l'école. »</blockquote>
                    <figcaption class="quote-card__attr">
                        <span class="quote-card__name">Une mère de famille</span>
                        <span class="quote-card__role">Pitoire, Maroua</span>
                    </figcaption>
                </figure>
                <figure class="quote-card quote-card--placeholder">
                    <blockquote class="quote-card__text">« J'ai reçu mon kit scolaire. Je peux étudier comme les autres. »</blockquote>
                    <figcaption class="quote-card__attr">
                        <span class="quote-card__name">Un élève</span>
                        <span class="quote-card__role">Lycée de Maroua</span>
                    </figcaption>
                </figure>
                <figure class="quote-card quote-card--placeholder">
                    <blockquote class="quote-card__text">« Cette association fait un travail que personne d'autre ne fait ici. »</blockquote>
                    <figcaption class="quote-card__attr">
                        <span class="quote-card__name">Un chef de quartier</span>
                        <span class="quote-card__role">Kakataré</span>
                    </figcaption>
                </figure>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     06 · NOS QUARTIERS
     ═══════════════════════════════════════════════════════ --}}
<section class="section--l section--tint">
    <div class="frame">
        <header class="section-head">
            <p class="meta">— Ancrage local</p>
            <h2 class="section-head__title">
                5 quartiers.<br>
                <em>Une même ambition.</em>
            </h2>
        </header>

        <div class="quartier-grid">
            @foreach([
                ['nom' => 'Pitoire',    'desc' => 'Notre siège et point de ralliement.'],
                ['nom' => 'Doualaré',   'desc' => 'Causeries éducatives régulières.'],
                ['nom' => 'Kakataré',   'desc' => 'Distribution de kits scolaires.'],
                ['nom' => 'Djarengol',  'desc' => 'Suivi des enfants décrocheurs.'],
                ['nom' => 'Zokok',      'desc' => 'Soutien scolaire et accompagnement.'],
            ] as $q)
                <div class="quartier-card fade">
                    <span class="quartier-card__mark"><x-icon name="map-pin" :size="18" /></span>
                    <h3 class="quartier-card__name">{{ $q['nom'] }}</h3>
                    <p class="quartier-card__desc">{{ $q['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     07 · ADHÉSION
     ═══════════════════════════════════════════════════════ --}}
<section class="section--l section--paper">
    <div class="frame">
        <header class="section-head">
            <p class="meta">— Rejoindre le mouvement</p>
            <h2 class="section-head__title">
                Deux manières de <em>s'engager à nos côtés.</em>
            </h2>
        </header>

        <div class="adhesion-grid">
            <div class="adhesion-option">
                <span class="adhesion-option__num">01</span>
                <h3 class="adhesion-option__title">Membre simple</h3>
                <p class="adhesion-option__text">
                    Un intérêt marqué pour l'éducation. Vous accédez aux informations,
                    participez aux activités publiques et soutenez nos actions par
                    votre cotisation annuelle.
                </p>
            </div>

            <div class="adhesion-option">
                <span class="adhesion-option__num">02</span>
                <h3 class="adhesion-option__title">Membre actif</h3>
                <p class="adhesion-option__text">
                    Une participation régulière aux activités, un droit de vote en
                    Assemblée Générale, et un accès complet à l'espace adhérent.
                    C'est le cœur battant de l'association.
                </p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <a href="{{ route('adherer') }}" class="btn btn-primary">
                Remplir le formulaire d'adhésion
                <x-icon name="arrow-right" :size="16" />
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     08 · DON
     ═══════════════════════════════════════════════════════ --}}
<section class="don-banner">
    <div class="frame-tight">
        <p class="meta" style="color: rgba(255,255,255,0.5); margin-bottom: 1.5rem;">— Soutenir</p>
        <h2>5 000 FCFA équipent un enfant pour toute une année scolaire.</h2>
        <p>Cahiers, stylos, règle, gomme, cartable. Un geste simple, un impact mesurable.</p>
        <a href="{{ route('don') }}" class="btn btn-green">
            <x-icon name="heart" :size="16" />
            Faire un don
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     09 · JOURNAL
     ═══════════════════════════════════════════════════════ --}}
<section class="section--l section--paper">
    <div class="frame">
        <header class="section-head">
            <p class="meta">— Le journal</p>
            <h2 class="section-head__title">Nouvelles du terrain.</h2>
        </header>

        @if($actualites->count() > 0)
            <div class="entry-list">
                @foreach($actualites as $actu)
                    <a href="{{ route('actualites.show', $actu->slug) }}" class="entry">
                        <div class="entry-date">
                            {{ ($actu->publie_le ?? $actu->created_at)->translatedFormat('d · m · Y') }}
                        </div>
                        <div>
                            <h3 class="entry-title">{{ $actu->titre }}</h3>
                            <p class="entry-excerpt">{{ $actu->extrait }}</p>
                        </div>
                        <div class="entry-image">
                            <img src="{{ $actu->image ? asset('storage/'.$actu->image) : asset('images/classe-rurale.jpg') }}"
                                 alt="{{ $actu->titre }}" loading="lazy">
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 2.5rem;">
                <a href="{{ route('actualites') }}" class="link-arrow">
                    Tous les articles
                    <x-icon name="arrow-right" :size="16" />
                </a>
            </div>
        @else
            <x-empty-state
                icon="book"
                title="Le journal se prépare"
                text="Les premières actualités seront publiées très bientôt. En attendant, découvrez nos actions."
                :action-url="route('actions')"
                action-label="Découvrir nos actions"
            />
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     10 · PARTENAIRES
     ═══════════════════════════════════════════════════════ --}}
<section class="section--m section--tint">
    <div class="frame">
        <header class="section-head" style="margin-bottom: 2rem;">
            <p class="meta">— Ils nous accompagnent</p>
            <h2 class="section-head__title" style="font-size: clamp(1.5rem, 3vw, 2rem);">
                Nos partenaires.
            </h2>
        </header>

        @if($partenaires->count() > 0)
            <div class="partner-row">
                @foreach($partenaires as $p)
                    @if($p->site_web)
                        <a href="{{ $p->site_web }}" target="_blank" rel="noopener" class="partner-logo">
                            <img src="{{ $p->logo ? asset('storage/'.$p->logo) : asset('images/placeholder-partner.png') }}"
                                 alt="{{ $p->nom }}" loading="lazy">
                        </a>
                    @else
                        <div class="partner-logo">
                            <img src="{{ $p->logo ? asset('storage/'.$p->logo) : asset('images/placeholder-partner.png') }}"
                                 alt="{{ $p->nom }}" loading="lazy">
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="para" style="color: var(--text-muted); max-width: 640px;">
                Nos premiers partenaires seront annoncés prochainement. Vous souhaitez
                soutenir nos actions ?
                <a href="{{ route('contact') }}" style="color: var(--blue); font-weight: 600;">Contactez-nous →</a>
            </p>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     11 · CTA CONTACT
     ═══════════════════════════════════════════════════════ --}}
<section class="cta-final">
    <div class="frame-narrow">
        <p class="meta" style="color: var(--blue); margin-bottom: 1.5rem;">— Restons en contact</p>
        <h2>Une question, un projet, une envie d'agir ?</h2>
        <p>Écrivez-nous. Nous répondons à chaque message.</p>
        <a href="{{ route('contact') }}" class="btn btn-outline">
            Nous contacter
            <x-icon name="arrow-right" :size="16" />
        </a>
    </div>
</section>

@endsection

@push('json-ld')
    <x-json-ld :data="\App\Support\Seo::websiteJsonLd()" />
@endpush