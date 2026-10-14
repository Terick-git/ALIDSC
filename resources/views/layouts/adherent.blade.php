<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace adhérent') | ALIDSC</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,800&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style nonce="{{ $cspNonce ?? '' }}">
        :root {
            --navy:  #10263B;
            --blue:  #0B6FB8;
            --green: #1F7A50;
            --paper: #FDFCFA;
            --line:  #E5E0D8;
            --ink:   #1A1A1A;
            --header-h-adh: 64px;
        }
        body {
            font-family: 'Source Sans 3', system-ui, sans-serif;
            background: var(--paper);
            color: var(--ink);
            margin: 0;
        }
        h1, h2, h3, .font-display {
            font-family: 'Bricolage Grotesque', Georgia, serif;
            letter-spacing: -.02em;
            color: var(--navy);
        }
        .adh-sidebar {
            background: var(--navy);
            color: rgba(255,255,255,0.72);
        }
        .adh-sidebar a {
            color: rgba(255,255,255,0.72);
            transition: background 180ms, color 180ms;
        }
        .adh-sidebar a:hover,
        .adh-sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .badge {
            display: inline-flex;
            padding: 2px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            background: #F3F4F6;
            color: #374151;
        }
        .badge-green { background: #D1FAE5; color: #065F46; }
        .badge-amber { background: #FEF3C7; color: #713F12; }
        .badge-red   { background: #FEE2E2; color: #7F1D1D; }
        .badge-blue  { background: #DBEAFE; color: #1E40AF; }

        /* ─── Drawer mobile ──────────────────────────── */
        .adh-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            max-width: 85vw;
            background: var(--navy);
            transform: translateX(-100%);
            transition: transform 260ms cubic-bezier(0.22, 1, 0.36, 1);
            z-index: 60;
            overflow-y: auto;
            padding: 1.5rem;
        }
        .adh-drawer.is-open { transform: translateX(0); }

        .adh-overlay {
            position: fixed;
            inset: 0;
            background: rgba(16, 38, 59, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: opacity 220ms, visibility 220ms;
            z-index: 55;
        }
        .adh-overlay.is-open {
            opacity: 1;
            visibility: visible;
        }

        @media (min-width: 1024px) {
            .adh-drawer,
            .adh-overlay { display: none; }
        }
    </style>
</head>
<body class="antialiased">

<div x-data="{ open: false }" class="min-h-screen lg:flex">

    {{-- ═══ SIDEBAR DESKTOP (fixe) ═══ --}}
    <aside class="adh-sidebar hidden lg:flex w-64 flex-shrink-0 flex-col p-6">
        <a href="{{ route('adherent.dashboard') }}" class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
            </div>
            <span class="font-display font-bold text-white text-lg">ALIDSC</span>
        </a>

        @include('adherent.partials.nav')

        <form method="POST" action="{{ route('adherent.logout') }}" class="mt-auto pt-6 border-t border-white/10">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded text-sm text-white/70 hover:bg-white/10 hover:text-white">
                Déconnexion
            </button>
        </form>
    </aside>

    {{-- ═══ DRAWER MOBILE ═══ --}}
    <div
        x-show="open"
        x-transition.opacity
        @click="open = false"
        class="adh-overlay"
        :class="{ 'is-open': open }">
    </div>

    <aside
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="adh-drawer"
        @keydown.escape.window="open = false">

        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('adherent.dashboard') }}" class="flex items-center gap-3" @click="open = false">
                <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                    <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
                </div>
                <span class="font-display font-bold text-white text-lg">ALIDSC</span>
            </a>
            <button @click="open = false" aria-label="Fermer le menu" style="color:rgba(255,255,255,0.6); font-size:1.5rem; line-height:1;">
                ×
            </button>
        </div>

        @include('adherent.partials.nav', ['closeOnClick' => true])

        <form method="POST" action="{{ route('adherent.logout') }}" class="mt-8 pt-6 border-t border-white/10">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded text-sm text-white/70 hover:bg-white/10 hover:text-white">
                Déconnexion
            </button>
        </form>
    </aside>

    {{-- ═══ CONTENU PRINCIPAL ═══ --}}
    <div class="flex-1 min-w-0">

        {{-- Header sticky avec hamburger mobile --}}
        <header class="bg-white border-b sticky top-0 z-40" style="border-color: var(--line);">
            <div class="px-4 sm:px-8 py-4 flex items-center gap-4">

                {{-- Hamburger mobile --}}
                <button
                    @click="open = true"
                    class="lg:hidden inline-flex items-center justify-center w-10 h-10 -ml-2 rounded"
                    aria-label="Ouvrir le menu"
                    style="color: var(--navy);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    @yield('header')
                </div>
            </div>
        </header>

        <main class="p-4 sm:p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-900 text-sm rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded">{{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900 text-sm rounded">{{ session('warning') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>