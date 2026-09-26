@extends('layouts.admin')
@section('title', 'Modifier — '.$partenaire->nom)
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier le partenaire</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.partenaires.update', $partenaire) }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf @method('PUT')

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Nom *</label>
            <input type="text" name="nom" value="{{ old('nom', $partenaire->nom) }}" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Type *</label>
            <select name="type" required class="w-full px-3 py-2 border rounded">
                @foreach(['ecole' => 'École', 'entreprise' => 'Entreprise', 'institution' => 'Institution', 'association' => 'Association', 'autre' => 'Autre'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('type', $partenaire->type)==$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Logo</label>
        @if($partenaire->logo)
            <div class="mb-2"><img src="{{ asset('storage/'.$partenaire->logo) }}" alt="" class="h-16 rounded border p-1 bg-white"></div>
        @endif
        <input type="file" name="logo" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver le logo actuel.</p>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Site web</label>
            <input type="url" name="site_web" value="{{ old('site_web', $partenaire->site_web) }}" class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $partenaire->email) }}" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Téléphone</label>
            <input type="text" name="telephone" value="{{ old('telephone', $partenaire->telephone) }}" class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ordre</label>
            <input type="number" name="ordre" value="{{ old('ordre', $partenaire->ordre) }}" min="0" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded">{{ old('description', $partenaire->description) }}</textarea>
    </div>

    <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="actif" value="1" @checked(old('actif', $partenaire->actif)) class="rounded">
        Actif
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Mettre à jour</button>
        <a href="{{ route('admin.partenaires.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection