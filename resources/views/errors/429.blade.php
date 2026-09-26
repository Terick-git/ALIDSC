<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trop de requêtes — ALIDSC</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage Grotesque:opsz,wght@9..144,400;9..144,500&family=Source+Sans+3:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #FDFCFA; color: #1A1A1A;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 2rem; text-align: center;
        }
        h1 { font-family: 'Bricolage Grotesque', Georgia, serif; color: #10263B; font-size: 4rem; margin-bottom: 1rem; }
        p { color: #4A4A4A; font-size: 1.125rem; max-width: 500px; margin: 0 auto 2rem; line-height: 1.6; }
        a {
            display: inline-block; padding: 1rem 2rem;
            background: #10263B; color: white; text-decoration: none;
            font-weight: 600; font-size: .9375rem;
        }
        a:hover { background: #0A1B2A; }
    </style>
</head>
<body>
    <div>
        <h1>429</h1>
        <p>{{ $message ?? 'Vous avez effectué trop de requêtes. Veuillez patienter avant de réessayer.' }}</p>
        <a href="{{ url()->previous() }}">← Retour</a>
    </div>
</body>
</html>
