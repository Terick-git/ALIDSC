@extends('layouts.admin')
@section('title', 'Témoignages')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Témoignages</h1>@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $temoignages->total() }} témoignage(s)</p>
    <a href="{{ route('admin.temoignages.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouveau témoignage</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Photo</th>
                <th class="px-4 py-3">Nom / Rôle</th>
                <th class="px-4 py-3">Citation</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($temoignages as $t)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($t->photo)
                            <img src="{{ asset('storage/'.$t->photo) }}" alt="" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                                {{ strtoupper(substr($t->nom, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $t->nom }}</div>
                        <div class="text-xs text-gray-500">{{ $t->role }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <div class="line-clamp-2 italic">« {{ Str::limit($t->citation, 80) }} »</div>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $t->categorie_label }}</td>
                    <td class="px-4 py-3">
                        @if($t->actif)<span class="badge badge-green">Actif</span>
                        @else<span class="badge">Inactif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.temoignages.edit', $t) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.temoignages.destroy', $t) }}" class="inline" data-confirm="Supprimer ?">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucun témoignage.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $temoignages->links() }}</div>
@endsection