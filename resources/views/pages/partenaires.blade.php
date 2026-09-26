@extends('layouts.app')
@section('title', 'Partenaires')
@section('description', 'Ils soutiennent l\'ALIDSC — écoles, entreprises, institutions.')
@section('content')

<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Partenaires</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">Ils nous <em>accompagnent.</em></h1>
    </div>
</section>

<section style="padding: 3rem 0 10rem;">
    <div class="frame">
        @if($partenaires->count())
            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px,1fr)); gap: 2rem;">
                @foreach($partenaires as $p)
                    <div class="fade" style="padding: 2rem; border: 1px solid var(--line);">
                        <div style="height: 80px; margin-bottom: 1.5rem; display:flex; align-items:center;">
                            @if($p->logo)<img src="{{ asset('storage/'.$p->logo) }}" alt="{{ $p->nom }}" style="max-height:80px; max-width:100%; object-fit:contain;">
                            @else <span class="display-3">{{ $p->nom }}</span>@endif
                        </div>
                        <h3 class="display-3" style="font-size: 1.125rem; margin-bottom:.5rem;">{{ $p->nom }}</h3>
                        <p class="meta" style="color: var(--blue); margin-bottom: 1rem;">{{ ucfirst($p->type) }}</p>
                        @if($p->description)<p class="para" style="font-size:.9375rem;">{{ $p->description }}</p>@endif
                        @if($p->site_web)<a href="{{ $p->site_web }}" target="_blank" class="meta" style="color: var(--blue);">Visiter le site →</a>@endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="para-lg" style="text-align:center;">Nos partenaires seront annoncés prochainement.</p>
        @endif
    </div>
</section>
@endsection