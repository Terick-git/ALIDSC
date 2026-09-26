@extends('layouts.adherent')
@section('title', 'Tableau de bord')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Bonjour, {{ $adherent->prenom }}.</h1>
    <p class="text-sm text-gray-500">Bienvenue dans votre espace adhérent</p>
@endsection

@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Catégorie</div>
        <div class="text-xl font-display text-[#10263B]">{{ $adherent->categorie_label }}</div>
    </div>
    <div class="bg-white p-6 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Statut</div>
        <div class="text-xl font-display text-[#10263B]">{{ $adherent->statut_demande_label }}</div>
    </div>
    <div class="bg-white p-6 rounded border">
        <div class="text-xs uppercase tracking-wider text-gray-500 mb-1">Membre depuis</div>
        <div class="text-xl font-display text-[#10263B]">{{ $adherent->date_adhesion?->translatedFormat('M Y') ?? '—' }}</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="{{ route('adherent.profile.edit') }}" class="block bg-white p-6 rounded border hover:border-[#10263B] transition">
        <h3 class="font-display text-lg text-[#10263B] mb-1">Mon profil</h3>
        <p class="text-xs text-gray-500">Modifier mes informations</p>
    </a>
    <a href="{{ route('adherent.documents') }}" class="block bg-white p-6 rounded border hover:border-[#10263B] transition">
        <h3 class="font-display text-lg text-[#10263B] mb-1">Documents</h3>
        <p class="text-xs text-gray-500">Comptes-rendus, statuts, rapports</p>
    </a>
    <a href="{{ route('adherent.actualites') }}" class="block bg-white p-6 rounded border hover:border-[#10263B] transition">
        <h3 class="font-display text-lg text-[#10263B] mb-1">Actualités internes</h3>
        <p class="text-xs text-gray-500">Infos réservées aux membres</p>
    </a>
    <a href="{{ route('home') }}" class="block bg-white p-6 rounded border hover:border-[#10263B] transition">
        <h3 class="font-display text-lg text-[#10263B] mb-1">Site public</h3>
        <p class="text-xs text-gray-500">Consulter le site</p>
    </a>
</div>

@endsection