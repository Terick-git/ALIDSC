@extends('layouts.app')

@section('title', $action->titre)
@section('description', $action->extrait)
@section('og_image', $action->image ? asset('storage/' . $action->image) : asset('images/logo-alidsc.jpg'))

@section('content')

{{-- EN-TÊTE DE L'ACTION --}}
<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <a href="{{ route('actions') }}" style="font-family: var(--display); font-style: italic; font-size: 0.9375rem; color: var(--blue); display: inline-block; margin-bottom: 3rem;">
            ← Toutes nos actions
        </a>

        <span class="meta" style="display:block; margin-bottom: 2rem; color: var(--blue);">— {{ $action->categorie }}</span>

        <h1 class="display-2" style="margin-bottom: 3rem;">
            {{ $action->titre }}
        </h1>

        <p class="para-lg" style="max-width: 720px;">
            {{ $action->extrait }}
        </p>
    </div>
</section>

{{-- IMAGE PLEINE LARGEUR --}}
@if($action->image)
    <section style="padding: 0;">
        <div class="frame" style="padding: 0;">
            <div style="aspect-ratio: 21/9; overflow: hidden; background: var(--line-soft);">
                <img src="{{ asset('storage/' . $action->image) }}" alt="{{ $action->titre }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </section>
@endif

{{-- CONTENU --}}
<section style="padding: 8rem 0;">
    <div class="frame-narrow">
        <div class="para-lg" style="white-space: pre-line; line-height: 1.75; color: var(--text);">
            {!! nl2br(e($action->contenu)) !!}
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="invitation" style="padding: 8rem 0;">
    <div class="frame-narrow">
        <h2 class="fade">
            Soutenez <em>cette action.</em>
        </h2>
        <a href="{{ route('don') }}" class="invitation-cta fade">
            Faire un don
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    </div>
</section>

@endsection