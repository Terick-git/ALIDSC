@extends('layouts.app')

@php
    $ogImage = $evenement->image
        ? asset('storage/' . $evenement->image)
        : asset('images/og-default.jpg');
@endphp

@section('title', $evenement->titre)
@section('description', $evenement->extrait)
@section('og_type', 'article')
@section('og_image', $ogImage)

@push('json-ld')
    <x-json-ld :data="\App\Support\Seo::eventJsonLd($evenement)" />
    <x-json-ld :data="\App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Accueil',    'url' => route('home')],
        ['name' => 'Événements', 'url' => route('evenements')],
        ['name' => $evenement->titre, 'url' => route('evenements.show', $evenement->slug)],
    ])" />
@endpush

@section('content')

<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <a href="{{ route('evenements') }}" style="font-family: var(--display); font-style: italic; color: var(--blue); display:inline-block; margin-bottom: 3rem;">← Tous les événements</a>

        <span class="meta" style="display:block; margin-bottom: 2rem; color: var(--blue);">
            — {{ $evenement->date_debut->translatedFormat('d F Y à H\hi') }}
        </span>

        <h1 class="display-2" style="margin-bottom: 3rem;">{{ $evenement->titre }}</h1>
        <p class="para-lg">{{ $evenement->extrait }}</p>

        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--line); display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            <div><span class="meta" style="color: var(--blue); display:block; margin-bottom:.25rem;">Lieu</span><p class="para" style="margin:0;">{{ $evenement->lieu }}@if($evenement->ville), {{ $evenement->ville }}@endif</p></div>
            @if($evenement->intervenants)<div><span class="meta" style="color: var(--blue); display:block; margin-bottom:.25rem;">Intervenants</span><p class="para" style="margin:0;">{{ $evenement->intervenants }}</p></div>@endif
            @if($evenement->lien_inscription)<div><span class="meta" style="color: var(--blue); display:block; margin-bottom:.25rem;">Inscription</span><a href="{{ $evenement->lien_inscription }}" target="_blank" class="para" style="color: var(--blue);">S'inscrire →</a></div>@endif
        </div>
    </div>
</section>

@if($evenement->image)
<section style="padding: 0;">
    <div class="frame" style="padding:0;">
        <div style="aspect-ratio: 21/9; overflow:hidden; background: var(--line-soft);">
            <img src="{{ asset('storage/'.$evenement->image) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
        </div>
    </div>
</section>
@endif

<section style="padding: 8rem 0;">
    <div class="frame-narrow">
        <div class="para-lg" style="white-space: pre-line; line-height: 1.75; color: var(--text);">
            {!! nl2br(e($evenement->contenu)) !!}
        </div>
    </div>
</section>

@endsection