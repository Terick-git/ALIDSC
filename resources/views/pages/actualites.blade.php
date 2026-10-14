@extends('layouts.app')

@section('title', 'Journal')
@section('description', 'Toutes les actualités de l\'ALIDSC — actions de terrain, événements, rapports et histoires.')

@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Journal</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Nouvelles du <em>terrain.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            Suivez nos actions au quotidien, nos avancées, nos rencontres et nos résultats.
        </p>
    </div>
</section>

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
            <x-empty-state
                icon="book"
                title="Le journal se prépare"
                text="Nos premières actualités seront publiées très bientôt. En attendant, découvrez nos actions concrètes sur le terrain."
                :action-url="route('actions')"
                action-label="Voir nos actions"
            />

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid var(--line);">
                <p class="meta" style="color: var(--blue); margin-bottom: 1.5rem;">— En attendant</p>
                <h3 class="display-3" style="margin-bottom: 2rem;">Suivez-nous au quotidien sur WhatsApp.</h3>
                <a href="https://wa.me/237696506940" target="_blank" rel="noopener" class="btn btn-green">
                    <x-icon name="arrow-right" :size="16" />
                    Discuter sur WhatsApp
                </a>
            </div>
        @endif
    </div>
</section>

@endsection