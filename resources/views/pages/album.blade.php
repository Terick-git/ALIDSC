@extends('layouts.app')
@section('title', $album->titre)
@section('description', Str::limit($album->description ?? '', 150))
@section('content')

<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <a href="{{ route('galerie') }}" style="font-family: var(--display); font-style: italic; color: var(--blue); display:inline-block; margin-bottom: 3rem;">← Toute la galerie</a>
        <h1 class="display-2" style="margin-bottom: 2rem;">{{ $album->titre }}</h1>
        @if($album->description)<p class="para-lg">{{ $album->description }}</p>@endif
        <p class="meta" style="margin-top:2rem;">
            {{ $album->photos->count() }} photo{{ $album->photos->count() > 1 ? 's' : '' }}
            @if($album->date_evenement) — {{ $album->date_evenement->translatedFormat('d F Y') }}@endif
            @if($album->lieu) — {{ $album->lieu }}@endif
        </p>
    </div>
</section>

<section style="padding: 2rem 0 10rem;">
    <div class="frame">
        @if($album->photos->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
                @foreach($album->photos as $photo)
                    <figure class="fade" style="margin:0;">
                        <div style="aspect-ratio: 1; overflow:hidden; background: var(--line-soft);">
                            <img src="{{ asset('storage/'.$photo->fichier) }}" alt="{{ $photo->legende }}" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        @if($photo->legende)<figcaption class="meta" style="margin-top:.75rem;">{{ $photo->legende }}</figcaption>@endif
                    </figure>
                @endforeach
            </div>
        @else
            <p class="para-lg" style="text-align:center;">Cet album ne contient pas encore de photos.</p>
        @endif
    </div>
</section>

@if($autresAlbums->count())
<section style="padding: 3rem 0 8rem; background: var(--paper-tint);">
    <div class="frame">
        <h2 class="display-3" style="margin-bottom: 3rem;">Autres albums</h2>
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px,1fr)); gap: 2rem;">
            @foreach($autresAlbums as $autre)
                <a href="{{ route('galerie.show', $autre->slug) }}">
                    <div style="aspect-ratio: 4/3; overflow:hidden;">
                        <img src="{{ $autre->couverture ? asset('storage/'.$autre->couverture) : asset('images/hero-cameroun.jpg') }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <h3 class="display-3" style="margin-top: 1rem; font-size: 1.125rem;">{{ $autre->titre }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection