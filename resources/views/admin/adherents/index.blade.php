@extends('layouts.admin')
@section('title', 'Adhérents')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Adhérents</h1>@endsection

@section('content')

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white p-4 rounded border"><div class="text-xs uppercase text-gray-500">Total</div><div class="text-2xl font-bold">{{ $stats['total'] }}</div></div>
    <div class="bg-white p-4 rounded border"><div class="text-xs uppercase text-gray-500">Actifs</div><div class="text-2xl font-bold text-green-600">{{ $stats['a_jour'] }}</div></div>
    <div class="bg-white p-4 rounded border"><div class="text-xs uppercase text-gray-500">En attente</div><div class="text-2xl font-bold text-amber-600">{{ $stats['en_attente'] }}</div></div>
    <div class="bg-white p-4 rounded border"><div class="text-xs uppercase text-gray-500">Expirés</div><div class="text-2xl font-bold text-red-600">{{ $stats['expire'] }}</div></div>
    <div class="bg-white p-4 rounded border"><div class="text-xs uppercase text-gray-500">Recettes</div><div class="text-2xl font-bold">{{ number_format($stats['recettes'], 0, ',', ' ') }} <span class="text-sm">F</span></div></div>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="recherche" value="{{ request('recherche') }}" placeholder="Nom, email, téléphone..." class="flex-1 px-3 py-2 border rounded text-sm">
    <select name="statut" class="px-3 py-2 border rounded text-sm">
        <option value="">Tous les statuts</option>
        @foreach(\App\Models\Adherent::statutsDemande() as $k => $v)
            <option value="{{ $k }}" @selected(request('statut')==$k)>{{ $v }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm">Filtrer</button>
    <a href="{{ route('admin.export.adherents', request()->query()) }}" class="px-4 py-2 border rounded text-sm">Export CSV</a>
</form>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Nom</th>
                <th class="px-4 py-3">Contact</th>
                <th class="px-4 py-3">Catégorie</th>
                <th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adherents as $a)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $a->nom_complet }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        <div>{{ $a->email }}</div>
                        <div class="text-xs">{{ $a->telephone }}</div>
                    </td>
                    <td class="px-4 py-3">{{ $a->categorie_label }}</td>
                    <td class="px-4 py-3">
                        <span class="badge @if($a->statut_demande==='active') badge-green @elseif($a->statut_demande==='en_attente') badge-amber @elseif($a->statut_demande==='refuse') badge-red @else badge @endif">
                            {{ $a->statut_demande_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.adherents.show', $a) }}" class="text-blue-600 hover:underline">Voir</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Aucun adhérent.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $adherents->withQueryString()->links() }}</div>
@endsection