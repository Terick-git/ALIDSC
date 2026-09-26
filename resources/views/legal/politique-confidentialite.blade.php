@extends('layouts.app')
@section('title', 'Politique de confidentialité')
@section('content')
<section style="padding: 12rem 0 8rem;">
    <div class="frame-narrow">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Légal</span>
        <h1 class="display-2" style="margin-bottom: 4rem;">Confidentialité</h1>

        <div class="para" style="line-height:1.8;">
            <h2 class="display-3" style="margin: 3rem 0 1rem; font-size:1.25rem;">Données collectées</h2>
            <p>Nous collectons uniquement les données nécessaires : nom, prénom, email, téléphone, adresse, pièce d'identité (pour les adhésions).</p>

            <h2 class="display-3" style="margin: 3rem 0 1rem; font-size:1.25rem;">Finalités</h2>
            <p>Gestion des adhésions, communication interne, envoi d'informations relatives à l'association.</p>

            <h2 class="display-3" style="margin: 3rem 0 1rem; font-size:1.25rem;">Conservation</h2>
            <p>Vos données sont conservées pendant la durée de votre adhésion, puis archivées pour une durée maximale de 5 ans.</p>

            <h2 class="display-3" style="margin: 3rem 0 1rem; font-size:1.25rem;">Vos droits</h2>
            <p>Vous disposez d'un droit d'accès, de rectification, d'effacement et de portabilité de vos données. Pour l'exercer, contactez-nous à <a href="mailto:contact@alidsc.org" style="color: var(--blue);">contact@alidsc.org</a>.</p>

            <h2 class="display-3" style="margin: 3rem 0 1rem; font-size:1.25rem;">Sécurité</h2>
            <p>Vos données sont stockées sur des serveurs sécurisés, protégées par chiffrement et accès restreint.</p>
        </div>
    </div>
</section>
@endsection