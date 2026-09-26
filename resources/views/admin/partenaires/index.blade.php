@extends('layouts.admin')
@section('title', 'Partenaires')
@section('header')
    <h1 class="text-2xl font-display text-[#10263B]">Partenaires</h1>
    <p class="text-sm text-gray-500">Écoles, entreprises, institutions</p>
@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $partenaires->total() }} partenaire(s)</p>
    <a href="{{ route('admin.partenaires.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouveau partenaire</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Logo</th>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Contact</th>
                <th class="px-4 py-3">Ordre</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($partenaires as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($p->logo)
                            <img src="{{ asset('storage/'.$p->logo) }}" alt="" class="w-12 h-12 object-contain bg-gray-50 rounded">
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-xs text-gray-400">—</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 font-medium">{{ $p->nom }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ ucfirst($p->type) }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        @if($p->site_web)<div class="truncate max-w-[180px]">{{ $p->site_web }}</div>@endif
                        @if($p->email)<div>{{ $p->email }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $p->ordre }}</td>
                    <td class="px-4 py-3">
                        @if($p->actif)<span class="badge badge-green">Actif</span>
                        @else<span class="badge">Inactif</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.partenaires.edit', $p) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.partenaires.destroy', $p) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">Aucun partenaire.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $partenaires->links() }}</div>
@endsection