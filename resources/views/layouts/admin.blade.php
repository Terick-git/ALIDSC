<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') | ALIDSC</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/bulk.js'])

    <style nonce="{{ $cspNonce ?? '' }}">
        :root {
            --navy: #10263B;
            --blue: #0B6FB8;
            --green: #1F7A50;
            --paper: #FFFFFF;
            --paper-tint: #EEF5FB;
            --ink: #22384D;
            --line: #CFDCE8;
        }
        body {
            font-family: 'Source Sans 3', system-ui, sans-serif;
            background: var(--paper);
            color: var(--ink);
        }
        h1, h2, h3, h4, .font-display {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 600;
            letter-spacing: -0.02em;
        }
        .admin-sidebar { background: var(--navy); }
        .admin-sidebar a { color: rgba(255,255,255,0.72); transition: background 180ms, color 180ms; }
        .admin-sidebar a:hover,
        .admin-sidebar a.active { background: rgba(255,255,255,0.1); color: white; }

        .badge {
            display: inline-flex; align-items: center;
            padding: 2px 10px; border-radius: 9999px;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .04em;
            background: #F3F4F6; color: #374151;
        }
        .badge-green  { background: #D1FAE5; color: #065F46; }
        .badge-amber  { background: #FEF3C7; color: #713F12; }
        .badge-red    { background: #FEE2E2; color: #7F1D1D; }
        .badge-navy   { background: #D9E2EC; color: #10263B; }
        .badge-blue   { background: #DBEAFE; color: #1E40AF; }
    </style>
</head>
<body class="antialiased">

    <div class="min-h-screen flex" x-data="adminSidebar()" x-init="init()">

        {{-- ═══ SIDEBAR DESKTOP ═══ --}}
        <aside class="admin-sidebar hidden lg:flex w-64 flex-shrink-0 flex-col p-6">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                    <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
                </div>
                <span class="font-display font-bold text-white text-lg">ALIDSC</span>
            </a>

            @include('admin.partials.nav')

            <div class="mt-auto pt-6 border-t border-white/10">
                <div class="flex items-center gap-2 text-[10px] text-white/30 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full"
                          :style="refreshing ? 'background:#F59E0B' : 'background:#10B981;'"></span>
                    <span x-text="refreshing ? 'Mise à jour...' : 'À jour'"></span>
                </div>
            </div>
        </aside>

        {{-- ═══ DRAWER MOBILE ═══ --}}
        <div
            x-show="mobileOpen"
            x-transition.opacity
            @click="mobileOpen = false"
            class="fixed inset-0 bg-black/50 z-50 lg:hidden"
            style="display: none;">
        </div>

        <aside
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="admin-sidebar fixed top-0 left-0 bottom-0 w-72 max-w-[85vw] z-50 overflow-y-auto p-6 lg:hidden"
            style="display: none;"
            @keydown.escape.window="mobileOpen = false">

            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3" @click="mobileOpen = false">
                    <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                        <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
                    </div>
                    <span class="font-display font-bold text-white text-lg">ALIDSC</span>
                </a>
                <button @click="mobileOpen = false" aria-label="Fermer"
                        style="color:rgba(255,255,255,0.6); font-size:1.5rem; line-height:1;">×</button>
            </div>

            @include('admin.partials.nav', ['mobileMode' => true])
        </aside>

        {{-- ═══ CONTENU ═══ --}}
        <div class="flex-1 min-w-0">

            <header class="bg-white border-b sticky top-0 z-40" style="border-color: var(--line);">
                <div class="px-4 sm:px-8 py-4 flex items-center justify-between gap-4">

                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        {{-- Hamburger mobile --}}
                        <button
                            @click="mobileOpen = true"
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

                    <div class="flex items-center gap-4 flex-shrink-0">
                        <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline text-sm text-gray-600 hover:text-gray-900">
                            Voir le site →
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="p-4 sm:p-8">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-900 text-sm rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-900 text-sm rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Script sidebar : nonce ajouté pour la CSP de production --}}
    <script nonce="{{ $cspNonce ?? '' }}">
        function adminSidebar() {
            return {
                counts: {
                    messages_non_lus:     {{ $sidebarCounts['messages_non_lus'] ?? 0 }},
                    adherents_en_attente: {{ $sidebarCounts['adherents_en_attente'] ?? 0 }},
                },
                refreshing: false,
                mobileOpen: false,

                init() {
                    setInterval(() => this.refresh(), 60000);
                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden) this.refresh();
                    });
                },

                async refresh() {
                    this.refreshing = true;
                    try {
                        const response = await fetch('{{ route('admin.sidebar-counts') }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            credentials: 'same-origin',
                        });
                        if (response.ok) {
                            this.counts = await response.json();
                        }
                    } catch (e) {
                        console.warn('Refresh sidebar échoué', e);
                    } finally {
                        this.refreshing = false;
                    }
                }
            }
        }
    </script>

    @stack('scripts')

</body>
</html>