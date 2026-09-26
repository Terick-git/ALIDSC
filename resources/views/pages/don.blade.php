@extends('layouts.app')

@section('title', 'Faire un don')
@section('description', 'Soutenez l\'ALIDSC — 5 000 FCFA équipent un enfant pour toute une année scolaire.')

@section('content')
<section style="padding: 12rem 0 6rem; background: var(--paper);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 3rem; color: var(--blue);">— Soutenir</span>
        <h1 class="display-2" style="margin-bottom: 3rem;">
            Votre don finance <em>un kit scolaire.</em>
        </h1>
        <p class="para-lg" style="max-width: 720px;">
            5 000 FCFA — cahiers, stylos, règle, gomme, cartable. Un enfant équipé pour toute une année d'école.
        </p>
    </div>
</section>

<section style="padding: 4rem 0 8rem;">
    <div class="frame-tight">
        <div class="disclaimer" style="margin-bottom: 3rem;">
            <strong>Paiement en ligne disponible prochainement.</strong>
            Pour faire un don dès maintenant, contactez-nous par téléphone ou WhatsApp.
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <a href="tel:+237690856383" style="padding: 2rem; border: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); display:block; margin-bottom: 1rem;">Par téléphone</span>
                <p class="display-3" style="margin: 0;">690 856 383</p>
            </a>
            <a href="https://wa.me/237696506940" target="_blank" style="padding: 2rem; border: 1px solid var(--line);">
                <span class="meta" style="color: var(--blue); display:block; margin-bottom: 1rem;">Par WhatsApp</span>
                <p class="display-3" style="margin: 0;">Discuter →</p>
            </a>
        </div>
    </div>
</section>
@endsection