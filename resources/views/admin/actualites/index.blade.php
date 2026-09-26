@extends('layouts.admin')
@section('title', 'Actualités')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Actualités</h1>
    <p class="text-sm text-gray-500">Gérez les articles publiés et internes.</p>
@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $actualites->total() }} article(s)</p>
    <a href="{{ route('admin.actualites.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouvelle actualité</a>
</div>

<div class="bg-white rounded border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr class="text-left text-xs uppercase text-gray-500">
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Auteur</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Publié le</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actualites as $a)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $a->titre }}</div>
                        <div class="text-xs text-gray-400">/{{ $a->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $a->auteur?->name ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($a->interne)<span class="badge badge-navy">Interne</span>
                        @elseif($a->publie)<span class="badge badge-green">Publié</span>
                        @else<span class="badge">Brouillon</span>@endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $a->publie_le?->format('d/m/Y') ?? '—' }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.actualites.edit', $a) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.actualites.destroy', $a) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun article.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $actualites->links() }}</div>
@endsection