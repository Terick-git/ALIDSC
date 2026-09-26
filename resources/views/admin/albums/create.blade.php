@extends('layouts.admin')
@section('title', 'Nouvel album')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Nouvel album</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.albums.store') }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full px-3 py-2 border rounded">
        @error('titre')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" maxlength="2000" class="w-full px-3 py-2 border rounded">{{ old('description') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Date de l'événement</label>
            <input type="date" name="date_evenement" value="{{ old('date_evenement') }}" class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Lieu</label>
            <input type="text" name="lieu" value="{{ old('lieu') }}" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
        <label class="flex items-center gap-2 text-sm mt-6">
            <input type="checkbox" name="publie" value="1" @checked(old('publie')) class="rounded"> Publier l'album
        </label>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Image de couverture</label>
        <input type="file" name="couverture" accept="image/*" class="w-full text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Photos (sélection multiple)</label>
        <input type="file" name="photos[]" accept="image/*" multiple class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">Vous pourrez en ajouter d'autres après création.</p>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Créer l'album</button>
        <a href="{{ route('admin.albums.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection