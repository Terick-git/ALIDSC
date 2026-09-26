@extends('layouts.admin')

@section('title', 'Modifier — '.$actualite->titre)

@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Modifier l'actualité</h1>
    <p class="text-sm text-gray-500">
        Créée le {{ $actualite->created_at->translatedFormat('d F Y') }}
        @if($actualite->publie_le) · Publiée le {{ $actualite->publie_le->translatedFormat('d F Y') }}@endif
    </p>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.actualites.update', $actualite) }}"
      enctype="multipart/form-data"
      class="bg-white p-6 rounded border space-y-5 max-w-3xl">
    @csrf
    @method('PUT')

    {{-- Titre --}}
    <div>
        <label class="block text-sm font-medium mb-1">Titre *</label>
        <input type="text" name="titre" value="{{ old('titre', $actualite->titre) }}" required
               class="w-full px-3 py-2 border rounded">
        @error('titre')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Extrait --}}
    <div>
        <label class="block text-sm font-medium mb-1">
            Extrait * <span class="text-xs text-gray-400">(max 500)</span>
        </label>
        <textarea name="extrait" rows="2" required maxlength="500"
                  class="w-full px-3 py-2 border rounded">{{ old('extrait', $actualite->extrait) }}</textarea>
        @error('extrait')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Contenu --}}
    <div>
        <label class="block text-sm font-medium mb-1">Contenu *</label>
        <textarea name="contenu" rows="12" required
                  class="w-full px-3 py-2 border rounded font-mono text-sm">{{ old('contenu', $actualite->contenu) }}</textarea>
        @error('contenu')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Image --}}
    <div>
        <label class="block text-sm font-medium mb-1">Image</label>
        @if($actualite->image)
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ asset('storage/'.$actualite->image) }}" alt=""
                     class="h-24 w-32 object-cover rounded border">
                <p class="text-xs text-gray-500">
                    Image actuelle<br>
                    <code class="text-[10px]">{{ $actualite->image }}</code>
                </p>
            </div>
        @endif
        <input type="file" name="image" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-1">Laisser vide pour conserver l'image actuelle.</p>
        @error('image')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Options --}}
    <div class="flex flex-wrap gap-6 pt-2 border-t">
        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="publie" value="1"
                   @checked(old('publie', $actualite->publie)) class="rounded">
            Publier (visible sur le site public)
        </label>

        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="interne" value="1"
                   @checked(old('interne', $actualite->interne)) class="rounded">
            Article interne (réservé aux adhérents)
        </label>
    </div>

    {{-- Note d'aide --}}
    @if($actualite->interne)
        <div class="p-3 bg-blue-50 border-l-4 border-blue-400 text-xs text-blue-900">
            ⚠️ Cet article est marqué « interne ». Il n'apparaîtra que dans l'espace adhérent, pas sur le site public.
        </div>
    @endif

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-4 border-t">
        <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
            Enregistrer les modifications
        </button>
        <a href="{{ route('admin.actualites.index') }}" class="px-5 py-2 border rounded text-sm">
            Annuler
        </a>

        @if($actualite->publie)
            <a href="{{ route('actualites.show', $actualite->slug) }}" target="_blank"
               class="ml-auto text-sm text-blue-600 hover:underline">
                Voir sur le site →
            </a>
        @endif
    </div>
</form>

@endsection