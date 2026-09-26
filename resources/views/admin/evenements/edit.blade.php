@extends('layouts.admin')
@section('title', 'Modifier événement')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Modifier l'événement</h1>@endsection

@section('content')
<form method="POST" action="{{ route('admin.evenements.update', $evenement) }}" enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf @method('PUT')

    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre', $evenement->titre) }}" required class="w-full px-3 py-2 border rounded">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Extrait *</label>
        <textarea name="extrait" rows="2" required maxlength="500" class="w-full px-3 py-2 border rounded">{{ old('extrait', $evenement->extrait) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Contenu *</label>
        <textarea name="contenu" rows="8" required class="w-full px-3 py-2 border rounded font-mono text-sm">{{ old('contenu', $evenement->contenu) }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Date de début *</label>
            <input type="datetime-local" name="date_debut" value="{{ old('date_debut', $evenement->date_debut->format('Y-m-d\TH:i')) }}" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Date de fin</label>
            <input type="datetime-local" name="date_fin" value="{{ old('date_fin', $evenement->date_fin?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Lieu *</label>
            <input type="text" name="lieu" value="{{ old('lieu', $evenement->lieu) }}" required class="w-full px-3 py-2 border rounded">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Ville</label>
            <input type="text" name="ville" value="{{ old('ville', $evenement->ville) }}" class="w-full px-3 py-2 border rounded">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Intervenants</label>
        <input type="text" name="intervenants" value="{{ old('intervenants', $evenement->intervenants) }}" class="w-full px-3 py-2 border rounded">
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Image</label>
            @if($evenement->image)
                <div class="mb-2"><img src="{{ asset('storage/'.$evenement->image) }}" alt="" class="h-20 rounded"></div>
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Statut *</label>
            <select name="statut" required class="w-full px-3 py-2 border rounded">
                @foreach(['brouillon'=>'Brouillon','publie'=>'Publié','annule'=>'Annulé'] as $k => $v)
                    <option value="{{ $k }}" @selected(old('statut', $evenement->statut)===$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div>
            <label class="block text-sm font-medium mb-1">Lien d'inscription</label>
            <input type="url" name="lien_inscription" value="{{ old('lien_inscription', $evenement->lien_inscription) }}" class="w-full px-3 py-2 border rounded">
        </div>
        <label class="flex items-center gap-2 text-sm mt-6">
            <input type="checkbox" name="inscription_requise" value="1" @checked(old('inscription_requise', $evenement->inscription_requise)) class="rounded">
            Inscription requise
        </label>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Mettre à jour</button>
        <a href="{{ route('admin.evenements.index') }}" class="px-5 py-2 border rounded text-sm">Annuler</a>
    </div>
</form>
@endsection