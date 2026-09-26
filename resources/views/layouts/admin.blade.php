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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

    <div class="min-h-screen flex">

        <aside class="admin-sidebar w-64 flex-shrink-0 p-6" x-data="adminSidebar()" x-init="init()">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full bg-white p-0.5 overflow-hidden">
                    <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC" class="w-full h-full object-cover rounded-full">
                </div>
                <span class="font-display font-bold text-white text-lg">ALIDSC</span>
            </a>

            <nav class="flex flex-col gap-1 text-sm">
                <a href="{{ route('dashboard') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Tableau de bord
                </a>

                <a href="{{ route('admin.actualites.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.actualites.*') ? 'active' : '' }}">
                    Actualités
                </a>

                <a href="{{ route('admin.actions.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.actions.*') ? 'active' : '' }}">
                    Actions
                </a>

                <a href="{{ route('admin.partenaires.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.partenaires.*') ? 'active' : '' }}">
                    Partenaires
                </a>

                <a href="{{ route('admin.adherents.index') }}"
                   class="flex items-center justify-between px-3 py-2 rounded font-medium {{ request()->routeIs('admin.adherents.*') ? 'active' : '' }}">
                    <span>Adhérents</span>
                    <template x-if="counts.adherents_en_attente > 0">
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold"
                              style="background: #F59E0B; color: white;"
                              x-text="counts.adherents_en_attente"></span>
                    </template>
                </a>

                <a href="{{ route('admin.documents.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                    Documents
                </a>

                <a href="{{ route('admin.temoignages.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.temoignages.*') ? 'active' : '' }}">
                    Témoignages
                </a>

                <a href="{{ route('admin.evenements.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.evenements.*') ? 'active' : '' }}">
                    Événements
                </a>

                <a href="{{ route('admin.albums.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.albums.*') ? 'active' : '' }}">
                    Albums
                </a>

                <a href="{{ route('admin.ressources.index') }}"
                   class="block px-3 py-2 rounded font-medium {{ request()->routeIs('admin.ressources.*') ? 'active' : '' }}">
                    Ressources
                </a>

                <a href="{{ route('admin.messages.index') }}"
                   class="flex items-center justify-between px-3 py-2 rounded font-medium {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <span>Messages</span>
                    <template x-if="counts.messages_non_lus > 0">
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-bold"
                              style="background: #DC2626; color: white;"
                              x-text="counts.messages_non_lus"></span>
                    </template>
                </a>
            </nav>

            <div class="mt-6 pt-4 border-t border-white/10">
                <div class="flex items-center gap-2 text-[10px] text-white/30 uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full"
                          :style="refreshing ? 'background:#F59E0B' : 'background:#10B981;'"></span>
                    <span x-text="refreshing ? 'Mise à jour...' : 'À jour'"></span>
                </div>
            </div>
        </aside>

        <div class="flex-1 min-w-0">

            <header class="bg-white border-b" style="border-color: var(--line);">
                <div class="px-8 py-4 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        @yield('header')
                    </div>

                    <div class="flex items-center gap-4 flex-shrink-0">
                        <a href="{{ route('home') }}" target="_blank" class="text-sm text-gray-600 hover:text-gray-900">
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

            <main class="p-8">

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