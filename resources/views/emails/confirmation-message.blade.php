<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8"></head>
<body style="font-family: Inter, Arial, sans-serif; background:#F7F3EC; padding: 40px; color: #1A1A1A;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 40px; border-top: 4px solid #0B6FB8;">
        <h1 style="font-family: Georgia, serif; color: #10263B; margin-top: 0;">Merci {{ $contact->nom }} !</h1>

        <p>Nous avons bien reçu votre message intitulé <strong>{{ $contact->sujet }}</strong>.</p>

        <p>Notre équipe vous répondra dans les plus brefs délais à l'adresse <strong>{{ $contact->email }}</strong>.</p>

        <p style="padding: 20px 0; border-top: 1px solid #E5E0D8; border-bottom: 1px solid #E5E0D8; font-style: italic; color: #4A4A4A;">
            « {{ \Illuminate\Support\Str::limit($contact->contenu, 200) }} »
        </p>

        <p style="margin-top: 30px;">
            Cordialement,<br>
            <strong>L'équipe ALIDSC</strong><br>
            <span style="color: #0B6FB8;">Étudiez, Rêvez, Réussissez.</span>
        </p>
    </div>
</body>
</html>