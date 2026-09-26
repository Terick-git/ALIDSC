@extends('layouts.app')

@section('title', 'Notre impact')
@section('description', 'Les résultats concrets de l\'ALIDSC sur la scolarisation dans l\'Extrême-Nord Cameroun.')

@section('content')

{{-- EN-TÊTE --}}
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Impact</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Des chiffres. <em>Des visages. Des vies.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Derrière chaque chiffre, il y a un enfant qui a repris le chemin de l'école. Voici ce que nous avons accompli depuis 2022.
        </p>
    </div>
</section>

{{-- CHIFFRES --}}
<section class="numbers" style="padding: 4rem 0 10rem;">
    <div class="frame">
        <div class="number-row fade">
            <div class="number-value">500<span class="plus">+</span></div>
            <div class="number-detail">
                <h3>Élèves accompagnés.</h3>
                <p>Des enfants qui auraient décroché sans intervention, aujourd'hui scolarisés ou en cours de raccrochage.</p>
            </div>
        </div>

        <div class="number-row fade">
            <div class="number-value">12</div>
            <div class="number-detail">
                <h3>Causeries éducatives organisées.</h3>
                <p>Dans les cinq quartiers les plus touchés de Maroua, en lien avec les chefs de quartier et les familles.</p>
            </div>
        </div>

        <div class="number-row fade">
            <div class="number-value">5000<span class="unit">FCFA</span></div>
            <div class="number-detail">
                <h3>Le coût d'un kit scolaire.</h3>
                <p>Cahiers, stylos, règle, gomme, cartable. Assez pour équiper un enfant sur toute une année scolaire.</p>
            </div>
        </div>

        <div class="number-row fade">
            <div class="number-value">5</div>
            <div class="number-detail">
                <h3>Quartiers de Maroua couverts.</h3>
                <p>Pitoire, Doualaré, Kakataré, Djarengol, Zokok — nous intervenons dans les zones les plus touchées.</p>
            </div>
        </div>
    </div>
</section>

{{-- TÉMOIGNAGES --}}
@if($temoignages->count() > 0)
    <section class="portrait" style="padding: 10rem 0;">
        <div class="frame">
            @foreach($temoignages as $t)
                <div class="portrait-grid" style="margin-bottom: 8rem;">
                    <div class="portrait-photo fade">
                        @if($t->photo)
                            <img src="{{ asset('storage/' . $t->photo) }}" alt="{{ $t->nom }}">
                        @else
                            <img src="{{ asset('images/enseignante-eleve.jpg') }}" alt="{{ $t->nom }}">
                        @endif
                    </div>

                    <div>
                        <p class="portrait-quote fade">
                            « {{ $t->citation }} »
                        </p>
                        <div class="portrait-attr fade">
                            <span class="portrait-name">{{ $t->nom }}</span>
                            <span class="portrait-role">{{ $t->role }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif

{{-- CTA --}}
<section class="invitation" style="padding: 8rem 0;">
    <div class="frame-narrow">
        <h2 class="fade">
            Contribuez à <em>notre impact.</em>
        </h2>
        <p class="fade" style="margin-bottom: 3rem;">
            5 000 FCFA — un kit scolaire. Un enfant équipé pour toute une année.
        </p>
        <a href="{{ route('don') }}" class="invitation-cta fade">
            Faire un don
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>

@endsection