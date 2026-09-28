@extends('layouts.admin')
@section('title', 'Ressources')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Ressources</h1>
<p class="text-sm text-gray-500">Bibliothèque numérique — études, publications, rapports</p>@endsection

@section('content')

<form method="GET" class="flex gap-3 mb-6">
    <select name="categorie" class="px-3 py-2 border rounded text-sm">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $k => $v)
            <option value="{{ $k }}" @selected(request('categorie')==$k)>{{ $v }}</option>
        @endforeach
    </select>
    <button class="px-4 py-2 border rounded text-sm">Filtrer</button>
    <a href="{{ route('admin.ressources.index') }}" class="px-4 py-2 text-gray-500 text-sm">Réinitialiser</a>
    <div class="ml-auto">
        <a href="{{ route('admin.ressources.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouvelle ressource</a>
    </div>
</form>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Année</th>
                <th class="px-4 py-3">Téléch.</th>
                <th class="px-4 py-3">Public</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ressources as $r)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $r->titre }}</div>
                        @if($r->auteur)<div class="text-xs text-gray-500">{{ $r->auteur }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $r->categorie_label }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $r->annee ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $r->telechargements }}</td>
                    <td class="px-4 py-3">
                        @if($r->public)<span class="badge badge-blue">Public</span>
                        @else<span class="badge">Adhérents</span>@endif
                    </td>
                    <td class="px-4 py-3">
                        @if($r->actif)<span class="badge badge-green">Actif</span>
                        @else<span class="badge">Inactif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.ressources.edit', $r) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.ressources.destroy', $r) }}" class="inline" data-confirm="Supprimer ?">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">Aucune ressource.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $ressources->withQueryString()->links() }}</div>
@endsection