<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace adhérent') | ALIDSC</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage Grotesque:opsz,wght@9..144,400;9..144,500;9..144,600&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy: #10263B;
            --terra: #0B6FB8;
            --paper: #FDFCFA;
            --line: #E5E0D8;
            --ink: #1A1A1A;
        }
        body { font-family: 'Inter', sans-serif; background: var(--paper); color: var(--ink); }
        h1, h2, h3 { font-family: 'Bricolage Grotesque', Georgia, serif; letter-spacing: -.02em; color: var(--navy); }
        .adh-sidebar { background: var(--navy); }
        .adh-sidebar a { color: rgba(255,255,255,.7); transition: all 200ms; }
        .adh-sidebar a:hover, .adh-sidebar a.active { background: rgba(255,255,255,.1); color: white; }
        .badge { display: inline-flex; padding: 2px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; background: #F3F4F6; color: #374151; }
        .badge-green { background: #D1FAE5; color: #065F46; }
        .badge-amber { background: #FEF3C7; color: #713F12; }
        .badge-red   { background: #FEE2E2; color: #7F1D1D; }
    </style>
</head>
<body class="antialiased">

<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="adh-sidebar w-64 flex-shrink-0 p-6">
        <a href="{{ route('adherent.dashboard') }}" class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
            </div>
            <span class="font-bold text-white text-lg" style="font-family:'Bricolage Grotesque',serif;">ALIDSC</span>
        </a>

        <p class="text-xs uppercase tracking-wider text-white/40 mb-2 px-3">Mon espace</p>
        <nav class="flex flex-col gap-1 text-sm mb-6">
            <a href="{{ route('adherent.dashboard') }}" class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.dashboard') ? 'active' : '' }}">Tableau de bord</a>
            <a href="{{ route('adherent.profile.edit') }}" class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.profile.*') ? 'active' : '' }}">Mon profil</a>
            <a href="{{ route('adherent.documents') }}" class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.documents*') ? 'active' : '' }}">Documents</a>
            <a href="{{ route('adherent.actualites') }}" class="block px-3 py-2 rounded font-medium {{ request()->routeIs('adherent.actualites*') ? 'active' : '' }}">Actualités internes</a>
        </nav>

        <p class="text-xs uppercase tracking-wider text-white/40 mb-2 px-3">Site public</p>
        <nav class="flex flex-col gap-1 text-sm">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded font-medium">← Retour au site</a>
        </nav>

        <form method="POST" action="{{ route('adherent.logout') }}" class="mt-8 pt-6 border-t border-white/10">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded text-sm text-white/70 hover:bg-white/10 hover:text-white">
                Déconnexion
            </button>
        </form>
    </aside>

    {{-- Contenu --}}
    <div class="flex-1 min-w-0">
        <header class="bg-white border-b" style="border-color: var(--line);">
            <div class="px-8 py-4">
                @yield('header')
            </div>
        </header>

        <main class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-900 text-sm rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
