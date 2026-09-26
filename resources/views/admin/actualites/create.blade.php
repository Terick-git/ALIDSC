@extends('layouts.admin')
@section('title', 'Nouvelle actualité')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Nouvelle actualité</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.actualites.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full px-3 py-2 border rounded">
        @error('titre')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Extrait * <span class="text-xs text-gray-400">(max 500)</span></label>
        <textarea name="extrait" rows="2" required maxlength="500" class="w-full px-3 py-2 border rounded">{{ old('extrait') }}</textarea>
        @error('extrait')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Contenu *</label>
        <textarea name="contenu" rows="12" required class="w-full px-3 py-2 border rounded font-mono text-sm">{{ old('contenu') }}</textarea>
        @error('contenu')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Image</label>
        <input type="file" name="image" accept="image/*" class="w-full text-sm">
    </div>

    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="publie" value="1" @checked(old('publie'))> Publier</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="interne" value="1" @checked(old('interne'))> Article interne (adhérents uniquement)</label>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
        <a href="{{ route('admin.actualites.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection
