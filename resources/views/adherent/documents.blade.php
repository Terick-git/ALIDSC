@extends('layouts.adherent')
@section('title', 'Mes documents')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Mes documents</h1>
    <p class="text-sm text-gray-500">Comptes-rendus, statuts et documents internes</p>
@endsection

@section('content')

@forelse($documents as $categorie => $liste)
    <div class="mb-8">
        <h2 class="text-xs uppercase tracking-wider text-[#0B6FB8] mb-3">
            {{ \App\Models\Document::categories()[$categorie] ?? $categorie }}
        </h2>
        <div class="bg-white rounded border divide-y">
            @foreach($liste as $doc)
                <a href="{{ route('adherent.documents.download', $doc) }}"
                   class="flex items-center justify-between p-4 hover:bg-gray-50">
                    <div>
                        <div class="font-medium">{{ $doc->titre }}</div>
                        @if($doc->description)
                            <div class="text-xs text-gray-500 mt-1">{{ $doc->description }}</div>
                        @endif
                    </div>
                    <span class="text-xs text-[#0B6FB8] font-medium">Télécharger ↓</span>
                </a>
            @endforeach
        </div>
    </div>
@empty
    <div class="bg-white p-8 rounded border text-center">
        <p class="text-gray-400">Aucun document disponible pour le moment.</p>
    </div>
@endforelse

@endsection