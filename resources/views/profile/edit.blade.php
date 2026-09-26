@extends('layouts.admin')

@section('title', 'Mon profil')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Mon profil</h1>
    <p class="text-sm text-gray-500">Gérez vos informations personnelles</p>
@endsection

@section('content')

<div class="max-w-3xl space-y-6">

    {{-- Informations du profil --}}
    <div class="bg-white p-6 rounded border">
        <h2 class="font-display text-lg text-[#10263B] mb-4">Informations personnelles</h2>
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Mot de passe --}}
    <div class="bg-white p-6 rounded border">
        <h2 class="font-display text-lg text-[#10263B] mb-4">Modifier le mot de passe</h2>
        @include('profile.partials.update-password-form')
    </div>

    {{-- AJOUT : 2FA --}}
    <div class="bg-white p-6 rounded border">
        <h2 class="font-display text-lg text-[#10263B] mb-4">Authentification à deux facteurs (2FA)</h2>
        @include('profile.partials.two-factor-form')
    </div>

    {{-- Suppression du compte --}}
    <div class="bg-white p-6 rounded border border-red-200">
        <h2 class="font-display text-lg text-red-700 mb-4">Supprimer le compte</h2>
        @include('profile.partials.delete-user-form')
    </div>

</div>

@endsection
