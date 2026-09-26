@extends('layouts.app')
@section('title', 'Ressources')
@section('description', 'Bibliothèque numérique de l\'ALIDSC — études, publications, rapports, journaux et ressources éducatives.')
@section('content')

<section style="padding: 12rem 0 4rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Ressources</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">La <em>bibliothèque.</em></h1>
    </div>
</section>

<section style="padding: 2rem 0 10rem;">
    <div class="frame">

        <form method="GET" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 1rem; align-items:end; margin-bottom: 4rem; padding-bottom: 2rem; border-bottom: 1px solid var(--line);">
            <div>
                <label class="meta" style="display:block; margin-bottom:.5rem;">Recherche</label>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, auteur..." style="width:100%; padding:.65rem 0; border:0; border-bottom:1px solid var(--line); background:transparent;">
            </div>
            <div>
                <label class="meta" style="display:block; margin-bottom:.5rem;">Catégorie</label>
                <select name="categorie" style="width:100%; padding:.65rem 0; border:0; border-bottom:1px solid var(--line); background:transparent;">
                    <option value="">Toutes</option>
                    @foreach($categories as $k => $v)
                        <option value="{{ $k }}" @selected(request('categorie')==$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="meta" style="display:block; margin-bottom:.5rem;">Année</label>
                <select name="annee" style="width:100%; padding:.65rem 0; border:0; border-bottom:1px solid var(--line); background:transparent;">
                    <option value="">Toutes</option>
                    @foreach($annees as $a)<option value="{{ $a }}" @selected(request('annee')==$a)>{{ $a }}</option>@endforeach
                </select>
            </div>
            <button type="submit" class="invitation-cta" style="cursor:pointer; background:none; border:0; border-bottom:1px solid var(--text);">Filtrer</button>
        </form>

        @if($ressources->count())
            <div style="display:grid; gap: 1rem;">
                @foreach($ressources as $r)
                    <a href="{{ route('ressources.show', $r->slug) }}" class="fade" style="display:grid; grid-template-columns: 100px 1fr auto; gap: 2rem; align-items:center; padding: 1.5rem 0; border-bottom: 1px solid var(--line);">
                        <div style="aspect-ratio: 3/4; overflow:hidden; background: var(--line-soft);">
                            @if($r->couverture)
                                <img src="{{ asset('storage/'.$r->couverture) }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:2rem;">{{ $r->icone_categorie }}</div>
                            @endif
                        </div>
                        <div>
                            <span class="meta" style="color: var(--blue);">{{ $r->categorie_label }}@if($r->annee) — {{ $r->annee }}@endif</span>
                            <h3 class="display-3" style="font-size: 1.25rem; margin-top:.5rem;">{{ $r->titre }}</h3>
                            @if($r->auteur)<p class="meta" style="margin-top:.5rem;">Par {{ $r->auteur }}</p>@endif
                        </div>
                        <span class="meta">↓ {{ $r->telechargements }}</span>
                    </a>
                @endforeach
            </div>
            <div style="margin-top: 4rem;">{{ $ressources->withQueryString()->links() }}</div>
        @else
            <p class="para-lg" style="text-align:center;">Aucune ressource ne correspond à votre recherche.</p>
        @endif
    </div>
</section>
@endsection