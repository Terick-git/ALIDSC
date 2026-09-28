@extends('layouts.admin')
@section('title', 'Actions')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Actions</h1>@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $actions->total() }} action(s)</p>
    <a href="{{ route('admin.actions.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouvelle action</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 w-16">Image</th>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Ordre</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($actions as $a)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($a->image)
                            <img src="{{ asset('storage/'.$a->image) }}" alt="" class="w-12 h-12 object-cover rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded"></div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $a->titre }}</div>
                        <div class="text-xs text-gray-400">/{{ $a->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $a->categorie }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $a->ordre }}</td>
                    <td class="px-4 py-3">
                        @if($a->actif)<span class="badge badge-green">Actif</span>
                        @else<span class="badge">Inactif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.actions.edit', $a) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.actions.destroy', $a) }}" class="inline" data-confirm="Supprimer ?">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucune action.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $actions->links() }}</div>
@endsection