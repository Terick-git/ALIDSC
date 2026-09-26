@extends('layouts.app')

@section('title', 'Journal')
@section('description', 'Toutes les actualités de l\'ALIDSC — actions de terrain, événements, rapports et histoires.')

@section('content')

{{-- EN-TÊTE --}}
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Journal</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Nouvelles du <em>terrain.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Suivez nos actions au quotidien, nos avancées, nos rencontres et nos résultats. Le journal de l'ALIDSC se met à jour régulièrement.
        </p>
    </div>
</section>

{{-- LISTE --}}
<section class="journal" style="padding: 4rem 0 10rem;">
    <div class="frame">
        @if($actualites->count() > 0)
            <div class="journal-list">
                @foreach($actualites as $actu)
                    <a href="{{ route('actualites.show', $actu->slug) }}" class="entry fade">
                        <div class="entry-date">
                            {{ $actu->publie_le?->translatedFormat('d M Y') ?? $actu->created_at->translatedFormat('d M Y') }}
                        </div>
                        <div>
                            <h3 class="entry-title">{{ $actu->titre }}</h3>
                            <p class="entry-excerpt">{{ $actu->extrait }}</p>
                        </div>
                        <div class="entry-image">
                            <img src="{{ $actu->image ? asset('storage/' . $actu->image) : asset('images/hero-cameroun.jpg') }}" alt="{{ $actu->titre }}">
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top: 5rem; text-align: center;">
                {{ $actualites->links() }}
            </div>
        @else
            <p class="para-lg" style="text-align: center; padding: 4rem 0;">
                Les premières actualités seront publiées bientôt.
            </p>
        @endif
    </div>
</section>

@endsection