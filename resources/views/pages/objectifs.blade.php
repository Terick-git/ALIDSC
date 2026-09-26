@extends('layouts.app')
@section('title', 'Nos objectifs')
@section('description', 'Les trois axes stratégiques de l\'ALIDSC : lutter contre le décrochage, promouvoir l\'excellence, susciter des vocations.')
@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Nos objectifs</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Trois combats. <em>Une seule ambition.</em>
        </h1>
    </div>
</section>

<section class="axes" style="padding: 6rem 0;">
    <div class="frame">
        <div class="axes-list">
            <div class="axe fade">
                <div class="axe-num">01</div>
                <h3 class="axe-title">Lutter contre le décrochage scolaire</h3>
                <p class="axe-desc">Identifier les enfants à risque, lever les freins matériels et familiaux, les raccrocher avant qu'il ne soit trop tard.</p>
            </div>
            <div class="axe fade">
                <div class="axe-num">02</div>
                <h3 class="axe-title">Promouvoir l'excellence</h3>
                <p class="axe-desc">Célébrer les réussites, valoriser les parcours exemplaires, créer des modèles inspirants pour toute une génération.</p>
            </div>
            <div class="axe fade">
                <div class="axe-num">03</div>
                <h3 class="axe-title">Susciter des vocations</h3>
                <p class="axe-desc">Ouvrir les horizons, connecter les jeunes à des professionnels, révéler des passions et des carrières possibles.</p>
            </div>
        </div>
    </div>
</section>

<section class="invitation" style="padding: 8rem 0;">
    <div class="frame-narrow">
        <h2 class="fade">Rejoignez <em>notre mouvement.</em></h2>
        <a href="{{ route('adherer') }}" class="invitation-cta fade">
            Devenir adhérent
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:18px;height:18px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>
</section>

@endsection