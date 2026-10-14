@extends('layouts.app')

@section('title', 'Page introuvable')
@section('robots', 'noindex, nofollow')

@section('content')

<section style="padding: 10rem 0 8rem; min-height: 60vh; display: flex; align-items: center;">
    <div class="frame-narrow" style="text-align: center;">

        <span class="meta" style="color: var(--blue); display: block; margin-bottom: 2rem;">— Erreur 404</span>

        <h1 class="display-2" style="margin-bottom: 2rem;">
            Cette page s'est <em>perdue en chemin.</em>
        </h1>

        <p class="para-lg" style="max-width: 560px; margin: 0 auto 3rem;">
            Le lien est peut-être erroné, ou la page a été déplacée.
            Revenons sur le droit chemin.
        </p>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <x-icon name="arrow-right" :size="16" />
                Retour à l'accueil
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline">
                Nous contacter
            </a>
        </div>

    </div>
</section>

@endsection