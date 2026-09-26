@extends('layouts.adherent')
@section('title', $actualite->titre)
@section('header')
    <a href="{{ route('adherent.actualites') }}" class="text-sm text-[#0B6FB8] hover:underline">← Toutes les actualités internes</a>
@endsection

@section('content')

<article class="max-w-3xl">
    <div class="text-xs uppercase tracking-wider text-[#0B6FB8] mb-3">
        {{ $actualite->publie_le?->translatedFormat('d F Y') ?? $actualite->created_at->translatedFormat('d F Y') }}
    </div>
    <h1 class="font-display text-3xl text-[#10263B] mb-4">{{ $actualite->titre }}</h1>
    <p class="text-lg text-gray-600 mb-6">{{ $actualite->extrait }}</p>

    @if($actualite->image)
        <div class="aspect-video overflow-hidden rounded mb-6 bg-gray-100">
            <img src="{{ asset('storage/'.$actualite->image) }}" alt="" class="w-full h-full object-cover">
        </div>
    @endif

    <div class="prose prose-sm max-w-none whitespace-pre-line text-gray-800 leading-relaxed">
        {!! nl2br(e($actualite->contenu)) !!}
    </div>
</article>

@endsection