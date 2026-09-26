@extends('layouts.app')

@section('title', 'Nos actions')
@section('description', 'Découvrez les actions concrètes de l\'ALIDSC sur le terrain : causeries éducatives, kits scolaires, accompagnement des familles.')

@section('content')

{{-- EN-TÊTE --}}
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Sur le terrain</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Ce que nous faisons, <em>concrètement.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Chaque action de l'ALIDSC est pensée pour lever un obstacle précis à la scolarisation. Nous n'agissons pas dans l'abstrait — nous allons voir les enfants, les familles, les écoles.
        </p>
    </div>
</section>

{{-- LISTE DES ACTIONS --}}
<section class="journal" style="padding: 4rem 0 10rem;">
    <div class="frame">
        @if($actions->count() > 0)
            <div class="journal-list">
                @foreach($actions as $action)
                    <a href="{{ route('actions.show', $action->slug) }}" class="entry fade">
                        <div class="entry-date">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            @if($action->categorie)
                                <br><span style="font-size: 0.75rem; opacity: 0.6; font-style: normal; letter-spacing: 0.15em; text-transform: uppercase;">{{ $action->categorie }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="entry-title">{{ $action->titre }}</h3>
                            <p class="entry-excerpt">{{ $action->extrait }}</p>
                        </div>
                        <div class="entry-image">
                            <img src="{{ $action->image ? asset('storage/' . $action->image) : asset('images/hero-cameroun.jpg') }}" alt="{{ $action->titre }}">
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="para-lg" style="text-align: center; padding: 4rem 0;">
                Nos actions seront publiées prochainement.
            </p>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="invitation" style="padding: 8rem 0;">
    <div class="frame-narrow">
        <h2 class="fade">
            Soutenez <em>nos actions.</em>
        </h2>
        <p class="fade" style="margin-bottom: 3rem;">
            Un don, une adhésion ou un peu de votre temps — chaque geste compte.
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