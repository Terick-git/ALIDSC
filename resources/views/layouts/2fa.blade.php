<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Vérification') | ALIDSC</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/alidsc.css'])

    <style nonce="{{ $cspNonce ?? '' }}">
        body {
            font-family: 'Source Sans 3', system-ui, sans-serif;
            color: #22384D;
            background: #FFFFFF;
        }
        h1, h2 {
            font-family: 'Bricolage Grotesque', system-ui, sans-serif;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #10263B;
        }
        .twofa-card {
            background: white;
            padding: 3rem 2.5rem;
            border: 1px solid #CFDCE8;
            border-radius: 4px;
            width: 100%;
            max-width: 460px;
        }
    </style>
</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">

    {{-- En-tête : logo non cliquable + mention « espace sécurisé » --}}
    <header style="padding: 1.5rem 2rem; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
        <span style="display:inline-flex; align-items:center; gap:.75rem; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:1.25rem; color:#10263B;">
            <img src="{{ asset('images/logo-alidsc.jpg') }}"
                 alt=""
                 style="width:40px; height:40px; border-radius:50%; object-fit:contain;">
            ALIDSC
        </span>

        <span style="font-size:0.6875rem; font-weight:600; letter-spacing:0.15em; text-transform:uppercase; color:#6B7E92;">
            Espace sécurisé
        </span>
    </header>

    <main style="flex:1; display:flex; align-items:center; justify-content:center; padding:2rem 1.25rem;">
        <div class="twofa-card">
            {{ $slot }}
        </div>
    </main>

    {{-- Pied de page : seul bouton autorisé ici = déconnexion --}}
    <footer style="padding: 1.5rem 2rem; text-align:center;">
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit"
                    style="font-size:.8125rem; color:#6B7E92; background:none; border:0; cursor:pointer; text-decoration:underline;">
                Se déconnecter
            </button>
        </form>
    </footer>

</body>
</html>