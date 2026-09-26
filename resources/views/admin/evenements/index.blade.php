@extends('layouts.admin')
@section('title', 'Événements')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Événements</h1>@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $evenements->total() }} événement(s)</p>
    <a href="{{ route('admin.evenements.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouvel événement</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Titre</th>
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Lieu</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evenements as $e)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-medium">{{ $e->titre }}</div>
                        <div class="text-xs text-gray-400">/{{ $e->slug }}</div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $e->date_debut->format('d/m/Y H:i') }}
                        @if($e->date_fin)<div class="text-xs text-gray-400">→ {{ $e->date_fin->format('d/m/Y H:i') }}</div>@endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $e->lieu }}</td>
                    <td class="px-4 py-3">
                        @if($e->statut === 'publie')<span class="badge badge-green">Publié</span>
                        @elseif($e->statut === 'annule')<span class="badge badge-red">Annulé</span>
                        @else<span class="badge">Brouillon</span>@endif
                    </td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('admin.evenements.edit', $e) }}" class="text-blue-600 hover:underline">Modifier</a>
                        <form method="POST" action="{{ route('admin.evenements.destroy', $e) }}" class="inline" onsubmit="return confirm('Supprimer ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">Aucun événement.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $evenements->links() }}</div>
@endsection