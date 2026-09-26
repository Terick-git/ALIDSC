<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Espace adhérent ALIDSC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage Grotesque:opsz,wght@9..144,400;9..144,500&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #FDFCFA; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        h1, h2 { font-family: 'Bricolage Grotesque', Georgia, serif; letter-spacing: -.02em; color: #10263B; }
        a { color: #0B6FB8; text-decoration: none; }
        a:hover { color: #10263B; }
        .card { background: white; padding: 3rem 2.5rem; border: 1px solid #E5E0D8; border-radius: 8px; width: 100%; max-width: 440px; }
        .card-header { text-align: center; margin-bottom: 2.5rem; }
        .card-header img { width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 1rem; display: block; }
        .field { margin-bottom: 1.5rem; }
        .field label { display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem; }
        .field input { width: 100%; padding: .75rem 0; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none; font-family: inherit; }
        .field input:focus { border-color: #0B6FB8; }
        .error { margin-top: .5rem; font-size: .8125rem; color: #C53030; }
        .btn { width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-size: .9375rem; font-weight: 600; cursor: pointer; font-family: inherit; }
        .btn:hover { background: #0A1B2A; }
        .alert { padding: .875rem 1rem; font-size: .875rem; margin-bottom: 1.5rem; border-left: 3px solid; }
        .alert-success { background: #ECFDF5; border-color: #059669; color: #065F46; }
        .alert-error { background: #FEF2F2; border-color: #C53030; color: #7F1D1D; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; font-size: .875rem; margin-bottom: 1.5rem; }
        .footer-link { text-align: center; margin-top: 2rem; font-size: .875rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC">
            </a>
            <h1 style="font-size:1.75rem;">Espace adhérent</h1>
            <p style="font-size:.875rem; color:#8A8A8A; margin-top:.5rem;">Connectez-vous à votre compte</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('adherent.login.post') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="field">
                <label for="password">Mot de passe</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div class="flex-between">
                <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer;">
                    <input type="checkbox" name="remember"> Se souvenir de moi
                </label>
                <a href="{{ route('adherent.password.forgot') }}">Mot de passe oublié ?</a>
            </div>
            <button type="submit" class="btn">Se connecter</button>
        </form>

        <p class="footer-link"><a href="{{ route('home') }}" style="color:#8A8A8A;">← Retour au site</a></p>
    </div>
</body>
</html>