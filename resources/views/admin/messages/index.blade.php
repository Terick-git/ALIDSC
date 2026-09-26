@extends('layouts.admin')
@section('title', 'Messages')
@section('header')<h1 class="text-2xl font-display text-[#10263B]">Messages</h1>@endsection

@section('content')
<div class="mb-4 flex justify-between items-center">
    <p class="text-sm text-gray-500">{{ $messages->total() }} message(s) — <strong>{{ $nonLus }}</strong> non lu(s)</p>
    <a href="{{ route('admin.export.messages') }}" class="px-4 py-2 border rounded text-sm">Export CSV</a>
</div>

<div class="bg-white rounded border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Expéditeur</th><th class="px-4 py-3">Sujet</th><th class="px-4 py-3">Date</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody>
            @forelse($messages as $m)
                <tr class="border-b hover:bg-gray-50 @if(!$m->lu) bg-amber-50/30 font-medium @endif">
                    <td class="px-4 py-3">{{ $m->nom }}<div class="text-xs text-gray-500">{{ $m->email }}</div></td>
                    <td class="px-4 py-3">{{ $m->sujet }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.messages.show', $m) }}" class="text-blue-600 hover:underline">Lire</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">Aucun message.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection