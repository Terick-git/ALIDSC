@extends('layouts.admin')
@section('title', 'Nouveau document')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Nouveau document</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full px-3 py-2 border rounded">
        @error('titre')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" maxlength="1000" class="w-full px-3 py-2 border rounded">{{ old('description') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <select name="categorie" required class="w-full px-3 py-2 border rounded">
                @foreach(\App\Models\Document::categories() as $k => $v)
                    <option value="{{ $k }}" @selected(old('categorie')==$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Fichier * <span class="text-xs text-gray-400">(PDF, DOC, XLS, PPT, JPG, PNG — max 20 Mo)</span></label>
        <input type="file" name="fichier" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" class="w-full text-sm">
        @error('fichier')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', true)) class="rounded"> Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
        <a href="{{ route('admin.documents.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection