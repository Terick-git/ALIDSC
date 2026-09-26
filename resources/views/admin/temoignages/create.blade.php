@extends('layouts.admin')
@section('title', 'Nouveau témoignage')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Nouveau témoignage</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.temoignages.store') }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Nom *</label>
            <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full px-3 py-2 border rounded">
            @error('nom')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Rôle *</label>
            <input type="text" name="role" value="{{ old('role') }}" required placeholder="Ex : Élève au Lycée de Maroua" class="w-full px-3 py-2 border rounded">
            @error('role')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Photo</label>
        <input type="file" name="photo" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">Format carré recommandé.</p>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Citation *</label>
        <textarea name="citation" rows="4" required class="w-full px-3 py-2 border rounded">{{ old('citation') }}</textarea>
        @error('citation')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <select name="categorie" required class="w-full px-3 py-2 border rounded">
                @foreach(\App\Models\Temoignage::categories() as $k => $v)
                    <option value="{{ $k }}" @selected(old('categorie')==$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre d'affichage</label>
            <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', true)) class="rounded"> Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
        <a href="{{ route('admin.temoignages.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection