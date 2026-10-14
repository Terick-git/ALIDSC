@extends('layouts.app')
@section('title', 'Galerie')
@section('description', 'Photos et albums des actions de terrain de l\'ALIDSC.')

@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Galerie</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">Des images, <em>des visages.</em></h1>
    </div>
</section>

<section style="padding: 3rem 0 10rem;">
    <div class="frame">
        @if($albums->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                @foreach($albums as $album)
                    <a href="{{ route('galerie.show', $album->slug) }}" class="fade" style="display:block;">
                        <div style="aspect-ratio: 4/3; overflow:hidden; background: var(--paper-tint);">
                            <img src="{{ $album->couverture ? asset('storage/'.$album->couverture) : asset('images/hero-cameroun.jpg') }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <h3 class="display-3" style="margin-top: 1.25rem; font-size: 1.25rem;">{{ $album->titre }}</h3>
                        <p class="meta">{{ $album->photos_count }} photo{{ $album->photos_count > 1 ? 's' : '' }}@if($album->date_evenement) — {{ $album->date_evenement->translatedFormat('M Y') }}@endif</p>
                    </a>
                @endforeach
            </div>
            <div style="margin-top: 5rem;">{{ $albums->links() }}</div>
        @else
            <x-empty-state
                icon="image"
                title="La galerie s'ouvre bientôt"
                text="Nous préparons une sélection de photos de nos actions à Maroua. Revenez très vite."
                :action-url="route('impact')"
                action-label="Voir notre impact"
            />

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid var(--line);">
                <p class="meta" style="color: var(--blue); margin-bottom: 1.5rem;">— En attendant</p>
                <h3 class="display-3" style="margin-bottom: 2rem;">Découvrez nos actions terrain.</h3>
                <a href="{{ route('actions') }}" class="btn btn-primary">
                    <x-icon name="arrow-right" :size="16" />
                    Voir nos actions
                </a>
            </div>
        @endif
    </div>
</section>

@endsection