@extends('layouts.adherent')
@section('title', 'Actualités internes')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Actualités internes</h1>
    <p class="text-sm text-gray-500">Informations réservées aux adhérents</p>
@endsection

@section('content')

@if($actualites->count())
    <div class="space-y-4">
        @foreach($actualites as $actu)
            <a href="{{ route('adherent.actualites.show', $actu) }}"
               class="block bg-white rounded border hover:border-[#10263B] transition p-6">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="text-xs uppercase tracking-wider text-[#0B6FB8] mb-2">
                            {{ $actu->publie_le?->translatedFormat('d F Y') ?? $actu->created_at->translatedFormat('d F Y') }}
                        </div>
                        <h3 class="font-display text-lg text-[#10263B] mb-2">{{ $actu->titre }}</h3>
                        <p class="text-sm text-gray-600">{{ $actu->extrait }}</p>
                    </div>
                    @if($actu->image)
                        <div class="w-32 h-20 flex-shrink-0 rounded overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/'.$actu->image) }}" alt="" class="w-full h-full object-cover">
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-6">{{ $actualites->links() }}</div>
@else
    <div class="bg-white p-8 rounded border text-center">
        <p class="text-gray-400">Aucune actualité interne pour le moment.</p>
    </div>
@endif

@endsection