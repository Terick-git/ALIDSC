@extends('layouts.admin')
@section('title', 'Modifier action')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier l'action</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.actions.update', $action) }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf @method('PUT')

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre', $action->titre) }}" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <input type="text" name="categorie" value="{{ old('categorie', $action->categorie) }}" required class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Extrait *</label>
        <textarea name="extrait" rows="2" required maxlength="500" class="w-full px-3 py-2 border rounded">{{ old('extrait', $action->extrait) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Contenu *</label>
        <textarea name="contenu" rows="10" required class="w-full px-3 py-2 border rounded font-mono text-sm">{{ old('contenu', $action->contenu) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            @if($action->image)
                <div class="mb-2"><img src="{{ asset('storage/'.$action->image) }}" alt="" class="h-20 rounded"></div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', $action->ordre) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', $action->actif)) class="rounded"> Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Mettre à jour</button>
        <a href="{{ route('admin.actions.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
    document.addEventListener('DOMContentLoaded', () => {
        new AlidscEditor('#contenu', {
            placeholder: 'Décrivez cette action concrète…',
        });
    });
</script>
@endpush

@endsection