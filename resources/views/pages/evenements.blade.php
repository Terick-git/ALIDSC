@extends('layouts.app')
@section('title', 'Événements')
@section('description', 'Séminaires, tables rondes, causeries éducatives et sessions de motivation de l\'ALIDSC.')

@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Événements</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">Rencontrons-nous <em>sur le terrain.</em></h1>
    </div>
</section>

@if($evenementsAVenir->count())
    <section style="padding: 3rem 0 8rem;">
        <div class="frame">
            <h2 class="display-3" style="margin-bottom: 3rem;">À venir</h2>
            <div class="journal-list">
                @foreach($evenementsAVenir as $evt)
                    <a href="{{ route('evenements.show', $evt->slug) }}" class="entry fade">
                        <div class="entry-date">{{ $evt->date_debut->translatedFormat('d M Y') }}</div>
                        <div>
                            <h3 class="entry-title">{{ $evt->titre }}</h3>
                            <p class="entry-excerpt">{{ $evt->extrait }}</p>
                            <p class="meta" style="margin-top:.75rem;">📍 {{ $evt->lieu }}@if($evt->ville) — {{ $evt->ville }}@endif</p>
                        </div>
                        <div class="entry-image">
                            <img src="{{ $evt->image ? asset('storage/'.$evt->image) : asset('images/hero-cameroun.jpg') }}" alt="">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if($evenementsPasses->count())
    <section style="padding: 3rem 0 10rem; background: var(--paper-tint);">
        <div class="frame">
            <h2 class="display-3" style="margin-bottom: 3rem;">Passés</h2>
            <div class="journal-list">
                @foreach($evenementsPasses as $evt)
                    <a href="{{ route('evenements.show', $evt->slug) }}" class="entry fade">
                        <div class="entry-date">{{ $evt->date_debut->translatedFormat('d M Y') }}</div>
                        <div>
                            <h3 class="entry-title">{{ $evt->titre }}</h3>
                            <p class="entry-excerpt">{{ $evt->extrait }}</p>
                        </div>
                        <div class="entry-image">
                            <img src="{{ $evt->image ? asset('storage/'.$evt->image) : asset('images/hero-cameroun.jpg') }}" alt="">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if(!$evenementsAVenir->count() && !$evenementsPasses->count())
    <section style="padding: 4rem 0 10rem;">
        <div class="frame">
            <x-empty-state
                icon="calendar"
                title="Nos prochains événements arrivent"
                text="Causeries éducatives, distributions de kits, rencontres avec les familles — l'agenda se remplit bientôt. Suivez-nous pour ne rien manquer."
                :action-url="route('contact')"
                action-label="Nous contacter"
            />

            <div style="margin-top: 4rem; padding-top: 4rem; border-top: 1px solid var(--line);">
                <p class="meta" style="color: var(--blue); margin-bottom: 1.5rem;">— En attendant</p>
                <h3 class="display-3" style="margin-bottom: 2rem;">Découvrez nos actions terrain.</h3>
                <a href="{{ route('actions') }}" class="btn btn-primary">
                    <x-icon name="arrow-right" :size="16" />
                    Voir nos actions
                </a>
            </div>
        </div>
    </section>
@endif

@endsection