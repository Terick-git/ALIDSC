@extends('layouts.app')

@section('title', 'Manifeste')
@section('description', 'L\'histoire, la mission et les valeurs de l\'ALIDSC — Association de Lutte contre les Inégalités et les Décrochages Scolaires au Cameroun.')

@section('content')

{{-- =========================================
     EN-TÊTE DE PAGE
     ========================================= --}}
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Manifeste</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Nous croyons que l'éducation est le levier le plus puissant pour <em>transformer une vie.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            L'ALIDSC est une association camerounaise apolitique et à but non lucratif, fondée en 2022 à Maroua, dans la région de l'Extrême-Nord. Nous agissons pour que chaque enfant puisse aller au bout de sa scolarité.
        </p>
    </div>
</section>

{{-- =========================================
     HISTOIRE
     ========================================= --}}
<section class="manifesto" style="padding-top: 4rem;">
    <div class="frame-tight">
        <span class="meta manifesto-label fade">— Notre histoire</span>
        <h2 class="fade">
            Tout a commencé par un constat simple : dans nos quartiers, des enfants abandonnaient l'école. Non par manque de capacité, mais parce que <em>les conditions n'étaient pas réunies.</em>
        </h2>
    </div>
</section>

{{-- =========================================
     MISSION — 2 colonnes éditoriales
     ========================================= --}}
<section style="padding: 8rem 0; background: var(--paper-tint);">
    <div class="frame">
        <div style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 6rem; align-items: start;">

            <div>
                <span class="meta" style="display:block; margin-bottom: 2rem; color: var(--blue);">— Notre mission</span>
                <h2 class="display-3">
                    Réduire les inégalités d'accès à l'éducation.
                </h2>
            </div>

            <div>
                <p class="para-lg" style="margin-bottom: 1.5rem;">
                    Nous travaillons sur trois fronts : la prévention du décrochage, la promotion de l'excellence, et la découverte de vocations. Chaque action est pensée pour lever, un par un, les obstacles qui empêchent les enfants de l'Extrême-Nord d'aller au bout de leur scolarité.
                </p>
                <p class="para">
                    Depuis 2022, nous sillonnons les quartiers de Maroua — Pitoire, Doualaré, Kakataré, Djarengol, Zokok — pour identifier les enfants à risque, dialoguer avec les familles, et mettre en place les solutions concrètes qui permettent de les raccrocher.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- =========================================
     VALEURS — Liste éditoriale
     ========================================= --}}
<section class="axes" style="padding: 10rem 0;">
    <div class="frame">
        <div class="axes-head fade">
            <span class="meta">— Nos valeurs</span>
            <h2>Quatre principes. <em>Jamais négociables.</em></h2>
        </div>

        <div class="axes-list">
            <div class="axe fade">
                <div class="axe-num">01</div>
                <h3 class="axe-title">Solidarité</h3>
                <p class="axe-desc">
                    Nous agissons collectivement. Chaque enfant que nous accompagnons est soutenu par une communauté entière — familles, écoles, chefs de quartier, partenaires.
                </p>
            </div>

            <div class="axe fade">
                <div class="axe-num">02</div>
                <h3 class="axe-title">Fraternité</h3>
                <p class="axe-desc">
                    Nous unissons les communautés autour de la cause éducative. Nos actions ne divisent pas — elles rassemblent.
                </p>
            </div>

            <div class="axe fade">
                <div class="axe-num">03</div>
                <h3 class="axe-title">Équité</h3>
                <p class="axe-desc">
                    Nous donnons les mêmes chances à chaque enfant, quelle que soit son origine, son genre ou la situation de sa famille.
                </p>
            </div>

            <div class="axe fade">
                <div class="axe-num">04</div>
                <h3 class="axe-title">Transparence</h3>
                <p class="axe-desc">
                    Nous gérons chaque ressource avec rigueur et rendons des comptes clairs à nos adhérents, partenaires et bailleurs.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- =========================================
     GOUVERNANCE
     ========================================= --}}
<section style="padding: 10rem 0; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Gouvernance</span>
        <h2 class="display-3" style="margin-bottom: 3rem;">
            Une association structurée, un cadre légal clair.
        </h2>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 4rem;">

            <div style="padding-top: 1.5rem; border-top: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); margin-bottom: 0.75rem; display:block;">Assemblée Générale</span>
                <p class="para">
                    Instance souveraine qui rassemble tous les adhérents à jour de cotisation. Elle valide les orientations stratégiques et élit le Bureau Exécutif.
                </p>
            </div>

            <div style="padding-top: 1.5rem; border-top: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); margin-bottom: 0.75rem; display:block;">Bureau Exécutif</span>
                <p class="para">
                    Équipe dirigeante élue, responsable de la mise en œuvre opérationnelle des décisions de l'AG et de la gestion quotidienne.
                </p>
            </div>

            <div style="padding-top: 1.5rem; border-top: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); margin-bottom: 0.75rem; display:block;">Équipes de Terrain</span>
                <p class="para">
                    Équipes mobiles qui interviennent directement dans les quartiers de Maroua, en lien avec les écoles et les familles.
                </p>
            </div>

            <div style="padding-top: 1.5rem; border-top: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); margin-bottom: 0.75rem; display:block;">Conseil Consultatif</span>
                <p class="para">
                    Cinq membres bénévoles, choisis pour leur expertise en éducation et leur attachement aux valeurs de l'association. Ils conseillent le Bureau Exécutif.
                </p>
            </div>

        </div>

        <div style="margin-top: 5rem; padding-top: 3rem; border-top: 1px solid var(--line);">
            <p class="para" style="font-size: 0.9375rem; color: var(--text-muted);">
                <strong style="color: var(--text); font-weight: 500;">Cadre légal :</strong>
                Association apolitique et à but non lucratif, conforme à la Loi N°90/053 du 19 décembre 1990 portant sur la liberté d'association au Cameroun.
                <br>
                <strong style="color: var(--text); font-weight: 500;">Siège social :</strong>
                Pitoire (Ancien décanat), derrière la CNPS, Maroua, Région de l'Extrême-Nord.
            </p>
        </div>
    </div>
</section>

{{-- =========================================
     CTA
     ========================================= --}}
<section class="invitation" style="padding: 8rem 0;">
    <div class="frame-narrow">
        <h2 class="fade">
            Rejoignez <em>notre mouvement.</em>
        </h2>
        <a href="{{ route('adherer') }}" class="invitation-cta fade">
            Devenir adhérent
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>

@endsection