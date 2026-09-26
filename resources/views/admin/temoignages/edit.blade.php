@extends('layouts.admin')
@section('title', 'Modifier témoignage')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier le témoignage</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.temoignages.update', $temoignage) }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf @method('PUT')

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Nom *</label>
            <input type="text" name="nom" value="{{ old('nom', $temoignage->nom) }}" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Rôle *</label>
            <input type="text" name="role" value="{{ old('role', $temoignage->role) }}" required class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Photo</label>
        @if($temoignage->photo)
            <div class="mb-2"><img src="{{ asset('storage/'.$temoignage->photo) }}" alt="" class="w-20 h-20 rounded-full object-cover"></div>
        @endif
        <input type="file" name="photo" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver.</p>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Citation *</label>
        <textarea name="citation" rows="4" required class="w-full px-3 py-2 border rounded">{{ old('citation', $temoignage->citation) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Catégorie *</label>
            <select name="categorie" required class="w-full px-3 py-2 border rounded">
                @foreach(\App\Models\Temoignage::categories() as $k => $v)
                    <option value="{{ $k }}" @selected(old('categorie', $temoignage->categorie)==$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', $temoignage->ordre) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', $temoignage->actif)) class="rounded"> Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Mettre à jour</button>
        <a href="{{ route('admin.temoignages.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection