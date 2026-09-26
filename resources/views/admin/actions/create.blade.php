@extends('layouts.admin')
@section('title', 'Nouvelle action')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Nouvelle action</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.actions.store') }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full px-3 py-2 border rounded">
            @error('titre')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <input type="text" name="categorie" value="{{ old('categorie') }}" required
                   placeholder="Ex : Sensibilisation, Matériel, Orientation..." class="w-full px-3 py-2 border rounded">
            @error('categorie')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Extrait * <span class="text-xs text-gray-400">(max 500)</span></label>
        <textarea name="extrait" rows="2" required maxlength="500" class="w-full px-3 py-2 border rounded">{{ old('extrait') }}</textarea>
        @error('extrait')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Contenu *</label>
        <textarea name="contenu" rows="10" required class="w-full px-3 py-2 border rounded font-mono text-sm">{{ old('contenu') }}</textarea>
        @error('contenu')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', true)) class="rounded"> Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
        <a href="{{ route('admin.actions.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection