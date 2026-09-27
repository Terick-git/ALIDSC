@extends('layouts.app')

@php
    $ogImage = $actualite->image
        ? asset('storage/' . $actualite->image)
        : asset('images/og-default.jpg');
@endphp

@section('title', $actualite->titre)
@section('description', $actualite->extrait)
@section('og_type', 'article')
@section('og_image', $ogImage)
@section('og_published_at', ($actualite->publie_le ?? $actualite->created_at)->toIso8601String())
@section('og_modified_at', $actualite->updated_at->toIso8601String())
@section('og_author', $actualite->auteur?->name ?? 'ALIDSC')

@push('json-ld')
    <x-json-ld :data="\App\Support\Seo::articleJsonLd($actualite)" />
    <x-json-ld :data="\App\Support\Seo::breadcrumbJsonLd([
        ['name' => 'Accueil', 'url' => route('home')],
        ['name' => 'Journal', 'url' => route('actualites')],
        ['name' => $actualite->titre, 'url' => route('actualites.show', $actualite->slug)],
    ])" />
@endpush

@section('content')

{{-- EN-TÊTE --}}
<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-narrow">
        <a href="{{ route('actualites') }}" class="link-arrow" style="margin-bottom: 3rem;">
            ← Tous les articles
        </a>

        <span class="meta" style="display: block; margin-bottom: 2rem; color: var(--blue);">
            — {{ ($actualite->publie_le ?? $actualite->created_at)->translatedFormat('d F Y') }}
        </span>

        <h1 class="display-2" style="margin-bottom: 2rem;">
            {{ $actualite->titre }}
        </h1>

        <p class="para-lg" style="max-width: 720px;">
            {{ $actualite->extrait }}
        </p>

        @if($actualite->auteur)
            <p style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--line); font-size: 0.875rem; color: var(--text-muted);">
                Par <strong style="color: var(--marine); font-weight: 600;">{{ $actualite->auteur->name }}</strong>
            </p>
        @endif
    </div>
</section>

{{-- IMAGE --}}
@if($actualite->image)
    <section style="padding: 0;">
        <div class="frame" style="padding: 0;">
            <div style="aspect-ratio: 21/9; overflow: hidden; background: var(--paper-tint);">
                <img src="{{ asset('storage/' . $actualite->image) }}"
                     alt="{{ $actualite->titre }}"
                     style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </section>
@endif

{{-- CONTENU --}}
<section style="padding: 8rem 0;">
    <div class="frame-narrow">
        <div class="para-lg" style="white-space: pre-line; line-height: 1.75; color: var(--text);">
            {!! nl2br(e($actualite->contenu)) !!}
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-final">
    <div class="frame-narrow">
        <h2>Soutenez notre travail.</h2>
        <p>Votre adhésion finance un kit scolaire pour un enfant.</p>
        <a href="{{ route('adherer') }}" class="btn btn-primary">Devenir adhérent</a>
    </div>
</section>

@endsection