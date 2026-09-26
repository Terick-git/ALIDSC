@extends('layouts.admin')
@section('title', 'Albums')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Albums photos</h1>
<p class="text-sm text-gray-500">Galeries des actions de terrain</p>@endsection

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-sm text-gray-500">{{ $albums->total() }} album(s)</p>
    <a href="{{ route('admin.albums.create') }}" class="px-4 py-2 bg-[#10263B] text-white rounded text-sm font-medium">+ Nouvel album</a>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
    @forelse($albums as $album)
        <div class="bg-white rounded border overflow-hidden">
            <div class="aspect-[4/3] bg-gray-100">
                @if($album->couverture)
                    <img src="{{ asset('storage/'.$album->couverture) }}" alt="" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300">Sans couverture</div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="font-medium truncate">{{ $album->titre }}</h3>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $album->photos_count }} photo(s)
                    @if($album->date_evenement) · {{ $album->date_evenement->format('d/m/Y') }}@endif
                </div>
                <div class="mt-2 flex items-center justify-between">
                    @if($album->publie)<span class="badge badge-green">Publié</span>
                    @else<span class="badge">Brouillon</span>@endif
                    <div class="space-x-2 text-xs">
                        <a href="{{ route('admin.albums.edit', $album) }}" class="text-blue-600 hover:underline">Gérer</a>
                        <form method="POST" action="{{ route('admin.albums.destroy', $album) }}" class="inline" onsubmit="return confirm('Supprimer cet album et toutes ses photos ?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Suppr.</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-10 text-gray-400">Aucun album.</div>
    @endforelse
</div>
<div class="mt-6">{{ $albums->links() }}</div>
@endsection