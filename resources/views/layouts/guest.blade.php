<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Connexion') | ALIDSC</title>

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
        .guest-card {
            background: white;
            padding: 3rem 2.5rem;
            border: 1px solid #CFDCE8;
            border-radius: 4px;
            width: 100%;
            max-width: 440px;
        }
        .guest-field { margin-bottom: 1.5rem; }
        .guest-field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #6B7E92;
            margin-bottom: 0.5rem;
        }
        .guest-field input {
            width: 100%;
            padding: 0.75rem 0.875rem;
            font-family: inherit;
            font-size: 1rem;
            color: #22384D;
            background: white;
            border: 1px solid #CFDCE8;
            border-radius: 4px;
            outline: none;
            transition: border-color 180ms, box-shadow 180ms;
        }
        .guest-field input:focus {
            border-color: #0B6FB8;
            box-shadow: 0 0 0 3px rgba(11,111,184,0.12);
        }
        .guest-error {
            margin-top: 0.375rem;
            font-size: 0.8125rem;
            color: #C53030;
        }
        .guest-btn {
            width: 100%;
            padding: 0.875rem 1rem;
            background: #0B6FB8;
            color: white;
            border: 0;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 180ms;
        }
        .guest-btn:hover { background: #0A5E9C; }
        .guest-alert {
            padding: 0.875rem 1rem;
            border-radius: 4px;
            border-left: 3px solid;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        .guest-alert-success { background: #E6F2EC; border-color: #1F7A50; color: #143F2C; }
        .guest-alert-error   { background: #FEF2F2; border-color: #C53030; color: #7F1D1D; }
    </style>
</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">

    <header style="padding: 1.5rem 2rem;">
        <a href="{{ route('home') }}"
           style="display:inline-flex; align-items:center; gap:.75rem; font-family:'Bricolage Grotesque',sans-serif; font-weight:800; font-size:1.25rem; color:#10263B;">
            <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="" style="width:40px; height:40px; border-radius:50%; object-fit:contain;">
            ALIDSC
        </a>
    </header>

    <main style="flex:1; display:flex; align-items:center; justify-content:center; padding:2rem 1.25rem;">
        <div class="guest-card">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
