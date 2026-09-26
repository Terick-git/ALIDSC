@extends('layouts.admin')
@section('title', 'Modifier — '.$album->titre)
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier l'album</h1>
<p class="text-sm text-gray-500">{{ $album->photos->count() }} photo(s)</p>@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-900 text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Colonne formulaire principal --}}
    <div class="lg:col-span-2 space-y-6">

        <form method="POST" action="{{ route('admin.albums.update', $album) }}" enctype="multipart/form-data"
              class="bg-white p-6 rounded border space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Titre *</label>
                <input type="text" name="titre" value="{{ old('titre', $album->titre) }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="3" maxlength="2000" class="w-full px-3 py-2 border rounded">{{ old('description', $album->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Date de l'événement</label>
                    <input type="date" name="date_evenement" value="{{ old('date_evenement', $album->date_evenement?->format('Y-m-d')) }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Lieu</label>
                    <input type="text" name="lieu" value="{{ old('lieu', $album->lieu) }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Ordre</label>
                    <input type="number" name="ordre" value="{{ old('ordre', $album->ordre) }}" min="0" class="w-full px-3 py-2 border rounded">
                </div>
                <label class="flex items-center gap-2 text-sm mt-6">
                    <input type="checkbox" name="publie" value="1" @checked(old('publie', $album->publie)) class="rounded"> Publié
                </label>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Couverture</label>
                @if($album->couverture)
                    <div class="mb-2"><img src="{{ asset('storage/'.$album->couverture) }}" alt="" class="h-20 rounded"></div>
                @endif
                <input type="file" name="couverture" accept="image/*" class="w-full text-sm">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
                <a href="{{ route('admin.albums.index') }}" class="px-5 py-2 border rounded text-sm">Retour</a>
            </div>
        </form>

        {{-- Ajout de photos --}}
        <form method="POST" action="{{ route('admin.albums.ajouter-photos', $album) }}" enctype="multipart/form-data"
              class="bg-white p-6 rounded border space-y-4">
            @csrf
            <h2 class="font-display text-lg text-[#10263B]">Ajouter des photos</h2>
            <input type="file" name="photos[]" accept="image/*" multiple required class="w-full text-sm">
            <button type="submit" class="px-5 py-2 bg-[#0B6FB8] text-white rounded text-sm font-medium">Uploader</button>
        </form>
    </div>

    {{-- Colonne photos --}}
    <div class="bg-white p-6 rounded border">
        <h2 class="font-display text-lg text-[#10263B] mb-4">Photos ({{ $album->photos->count() }})</h2>

        @if($album->photos->isEmpty())
            <p class="text-sm text-gray-400">Aucune photo pour le moment.</p>
        @else
            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-1">
                @foreach($album->photos as $photo)
                    <div class="border rounded p-2">
                        <img src="{{ asset('storage/'.$photo->fichier) }}" alt="" class="w-full aspect-square object-cover rounded mb-2">

                        <form method="POST" action="{{ route('admin.photos.maj', $photo) }}" class="space-y-2">
                            @csrf @method('PATCH')
                            <input type="text" name="legende" value="{{ $photo->legende }}" placeholder="Légende..."
                                   class="w-full px-2 py-1 border rounded text-xs">
                            <button class="text-xs text-blue-600 hover:underline">Enregistrer la légende</button>
                        </form>

                        <form method="POST" action="{{ route('admin.photos.supprimer', $photo) }}"
                              onsubmit="return confirm('Supprimer cette photo ?')" class="mt-1">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection