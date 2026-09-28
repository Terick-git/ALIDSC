@extends('layouts.admin')
@section('title', 'Documents')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Documents</h1>
<p class="text-sm text-gray-500">Comptes-rendus, statuts, rapports — accessibles aux adhérents</p>@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $documents->total() }} document(s)</p>
    <a href="{{ route('admin.documents.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouveau document</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Ordre</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $d)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $d->titre }}</div>
                        @if($d->description)<div class="text-xs text-gray-500">{{ Str::limit($d->description, 60) }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->categorie_label }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $d->ordre }}</td>
                    <td class="px-4 py-3">
                        @if($d->actif)<span class="badge badge-green">Actif</span>
                        @else<span class="badge">Inactif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.documents.edit', $d) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.documents.destroy', $d) }}" class="inline" data-confirm="Supprimer ?">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Aucun document.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $documents->links() }}</div>
@endsection