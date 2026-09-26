@extends('layouts.app')
@section('title', $ressource->titre)
@section('description', Str::limit($ressource->description ?? '', 150))
@section('content')

<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <a href="{{ route('ressources') }}" style="font-family: var(--display); font-style: italic; color: var(--blue); display:inline-block; margin-bottom: 3rem;">← Toutes les ressources</a>
        <span class="meta" style="display:block; margin-bottom: 2rem; color: var(--blue);">{{ $ressource->categorie_label }}@if($ressource->annee) — {{ $ressource->annee }}@endif</span>
        <h1 class="display-2" style="margin-bottom: 2rem;">{{ $ressource->titre }}</h1>
        @if($ressource->auteur)<p class="meta">Par {{ $ressource->auteur }}</p>@endif
    </div>
</section>

<section style="padding: 4rem 0 8rem;">
    <div class="frame" style="display:grid; grid-template-columns: 300px 1fr; gap: 4rem; align-items:start;">
        <div>
            @if($ressource->couverture)
                <div style="aspect-ratio:3/4; overflow:hidden; background: var(--line-soft);">
                    <img src="{{ asset('storage/'.$ressource->couverture) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                </div>
            @endif
            <a href="{{ route('ressources.telecharger', $ressource->slug) }}" class="invitation-cta" style="margin-top: 2rem; display:inline-flex;">Télécharger ↓</a>
            <p class="meta" style="margin-top:1rem;">{{ $ressource->telechargements }} téléchargements</p>
        </div>
        <div>
            @if($ressource->description)<p class="para-lg">{!! nl2br(e($ressource->description)) !!}</p>@endif
        </div>
    </div>
</section>

@if($similaires->count())
<section style="padding: 4rem 0 8rem; background: var(--paper-tint);">
    <div class="frame">
        <h2 class="display-3" style="margin-bottom: 2rem;">À lire aussi</h2>
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(240px,1fr)); gap: 2rem;">
            @foreach($similaires as $s)
                <a href="{{ route('ressources.show', $s->slug) }}">
                    <h3 class="display-3" style="font-size:1.125rem;">{{ $s->titre }}</h3>
                    <p class="meta">{{ $s->categorie_label }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection