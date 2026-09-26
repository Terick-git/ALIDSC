@extends('layouts.admin')

@section('title', 'Message')

@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">{{ $message->sujet }}</h1>
    <p class="text-sm text-gray-500">
        Reçu le {{ $message->created_at->translatedFormat('d F Y à H\hi') }}
        @if($message->lu) · <span class="text-green-600">Lu</span>@endif
    </p>
@endsection

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

    <div class="lg:col-span-2 bg-white p-6 rounded border">
        <div class="prose prose-sm max-w-none whitespace-pre-line">{{ $message->contenu }}</div>
    </div>

    <div class="bg-white p-6 rounded border">
        <h3 class="font-display text-base mb-3 text-[#10263B]">Expéditeur</h3>
        <dl class="text-sm space-y-3">
            <div>
                <dt class="text-gray-500 text-xs uppercase">Nom</dt>
                <dd>{{ $message->nom }}</dd>
            </div>
            <div>
                <dt class="text-gray-500 text-xs uppercase">Email</dt>
                <dd><a href="mailto:{{ $message->email }}" class="text-blue-600 hover:underline">{{ $message->email }}</a></dd>
            </div>
            @if($message->telephone)
                <div>
                    <dt class="text-gray-500 text-xs uppercase">Téléphone</dt>
                    <dd><a href="tel:{{ $message->telephone }}" class="text-blue-600 hover:underline">{{ $message->telephone }}</a></dd>
                </div>
            @endif
        </dl>

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->sujet) }}"
               class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
                Répondre par email
            </a>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                  onsubmit="return confirm('Supprimer définitivement ce message ?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 border border-red-300 text-red-600 rounded text-sm hover:bg-red-50">
                    Supprimer
                </button>
            </form>
        </div>

        <a href="{{ route('admin.messages.index') }}" class="block mt-4 text-sm text-gray-500 hover:text-gray-900">
            ← Retour à la liste
        </a>
    </div>

</div>

@endsection
