@extends('layouts.admin')
@section('title', 'Modifier ressource')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier la ressource</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.ressources.update', $ressource) }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf @method('PUT')

    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre', $ressource->titre) }}" required class="w-full px-3 py-2 border rounded">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="4" maxlength="2000" class="w-full px-3 py-2 border rounded">{{ old('description', $ressource->description) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <select name="categorie" required class="w-full px-3 py-2 border rounded">
                @foreach(\App\Models\Ressource::categories() as $k => $v)
                    <option value="{{ $k }}" @selected(old('categorie', $ressource->categorie)==$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Année</label>
            <input type="number" name="annee" value="{{ old('annee', $ressource->annee) }}" min="2000" max="{{ date('Y')+1 }}" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Auteur</label>
            <input type="text" name="auteur" value="{{ old('auteur', $ressource->auteur) }}" class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', $ressource->ordre) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Fichier</label>
        <p class="text-xs text-gray-500 mb-2">Fichier actuel : <code>{{ $ressource->fichier }}</code></p>
        <input type="file" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="w-full text-sm">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Couverture</label>
        @if($ressource->couverture)
            <div class="mb-2"><img src="{{ asset('storage/'.$ressource->couverture) }}" alt="" class="h-24 rounded"></div>
        @endif
        <input type="file" name="couverture" accept="image/*" class="w-full text-sm">
    </div>

    <div class="flex gap-6">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="public" value="1" @checked(old('public', $ressource->public)) class="rounded"> Visible publiquement
        </label>
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="actif" value="1" @checked(old('actif', $ressource->actif)) class="rounded"> Actif
        </label>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Mettre à jour</button>
        <a href="{{ route('admin.ressources.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection