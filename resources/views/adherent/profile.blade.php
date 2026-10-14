@extends('layouts.adherent')
@section('title', 'Mon profil')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Mon profil</h1>
    <p class="text-sm text-gray-500">Gérez vos informations personnelles</p>
@endsection

@section('content')

<div class="max-w-3xl space-y-6">

    {{-- ⚠️ Bannière "changement de mot de passe requis" --}}
    @if($adherent->must_change_password)
        <div class="p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900 text-sm rounded">
            <strong>Changement de mot de passe requis.</strong>
            Vous utilisez actuellement un mot de passe temporaire.
            Pour des raisons de sécurité, vous devez le changer avant de pouvoir
            accéder au reste de votre espace.
        </div>
    @endif

    {{-- Informations --}}
    <div class="bg-white p-6 rounded border">
        <h2 class="font-display text-lg text-[#10263B] mb-4">Informations personnelles</h2>
        <form method="POST" action="{{ route('adherent.profile.update') }}" class="space-y-4">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Nom *</label>
                    <input type="text" name="nom" value="{{ old('nom', $adherent->nom) }}" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Prénom *</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $adherent->prenom) }}" required class="w-full px-3 py-2 border rounded">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium mb-1">Téléphone *</label>
                    <input type="text" name="telephone" value="{{ old('telephone', $adherent->telephone) }}" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville', $adherent->ville) }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $adherent->adresse) }}" class="w-full px-3 py-2 border rounded">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Établissement</label>
                <input type="text" name="etablissement" value="{{ old('etablissement', $adherent->etablissement) }}" class="w-full px-3 py-2 border rounded">
            </div>

            <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">Enregistrer</button>
        </form>
    </div>

    {{-- Mot de passe --}}
    <div class="bg-white p-6 rounded border {{ $adherent->must_change_password ? 'border-amber-400 ring-2 ring-amber-100' : '' }}">
        <h2 class="font-display text-lg text-[#10263B] mb-4">
            @if($adherent->must_change_password)
                Changer mon mot de passe (obligatoire)
            @else
                Changer mon mot de passe
            @endif
        </h2>
        <form method="POST" action="{{ route('adherent.profile.password') }}" class="space-y-4">
            @csrf @method('PATCH')

            @error('current_password')<p class="text-red-600 text-xs">{{ $message }}</p>@enderror

            <div>
                <label class="block text-sm font-medium mb-1">Mot de passe actuel *</label>
                <input type="password" name="current_password" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nouveau mot de passe *</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border rounded">
                <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères.</p>
                @error('password')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirmation *</label>
                <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded">
            </div>

            <button type="submit" class="px-5 py-2 bg-[#10263B] text-white rounded text-sm font-medium">
                Modifier le mot de passe
            </button>
        </form>
    </div>

</div>

@endsection