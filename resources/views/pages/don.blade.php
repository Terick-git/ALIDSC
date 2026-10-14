@extends('layouts.app')

@section('title', 'Faire un don')
@section('description', 'Soutenez l\'ALIDSC — 5 000 FCFA équipent un enfant pour toute une année scolaire.')

@push('styles')
<style nonce="{{ $cspNonce ?? '' }}">
/* ═══════════════════════════════════════════════════════
   PAGE DON — Design éditorial premium
   ═══════════════════════════════════════════════════════ */

/* ─── Bandeau impact ─────────────────────────────────── */
.don-impact {
    display: grid;
    grid-template-columns: 1fr 1.15fr;
    gap: 5rem;
    align-items: center;
    padding: 4rem 0;
    border-top: 1px solid var(--line);
    border-bottom: 1px solid var(--line);
}
.don-impact__figure {
    aspect-ratio: 4/5;
    overflow: hidden;
    background: var(--paper-tint);
}
.don-impact__figure img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.don-impact__quote {
    font-family: var(--display);
    font-weight: 400;
    font-size: clamp(1.5rem, 2.6vw, 2rem);
    line-height: 1.25;
    letter-spacing: -0.02em;
    color: var(--marine);
    margin-bottom: 2.5rem;
    text-wrap: balance;
}
.don-impact__quote em {
    font-style: italic;
    color: var(--blue);
}
.don-impact__stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--line);
}
.don-impact__stat-value {
    font-family: var(--display);
    font-weight: 800;
    font-size: 2.5rem;
    line-height: 1;
    letter-spacing: -0.04em;
    color: var(--marine);
}
.don-impact__stat-label {
    font-size: 0.8125rem;
    color: var(--text-muted);
    margin-top: 0.5rem;
    display: block;
}
@media (max-width: 900px) {
    .don-impact { grid-template-columns: 1fr; gap: 3rem; }
    .don-impact__figure { aspect-ratio: 16/10; }
}

/* ─── Layout 2 colonnes ──────────────────────────────── */
.don-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 5rem;
    align-items: start;
}
@media (max-width: 1024px) {
    .don-layout { grid-template-columns: 1fr; gap: 3rem; }
}

/* ─── Étapes ─────────────────────────────────────────── */
.don-step {
    padding: 3rem 0;
    border-bottom: 1px solid var(--line);
}
.don-step:last-child { border-bottom: 0; padding-bottom: 0; }
.don-step__head {
    display: flex;
    align-items: baseline;
    gap: 1.5rem;
    margin-bottom: 2rem;
}
.don-step__num {
    font-family: var(--display);
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--blue);
}
.don-step__title {
    font-family: var(--display);
    font-weight: 600;
    font-size: 1.25rem;
    letter-spacing: -0.02em;
    color: var(--marine);
}

/* ─── Chips montant ──────────────────────────────────── */
.don-amounts {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-bottom: 1.75rem;
}
.don-chip {
    position: relative;
    padding: 1.75rem 1rem;
    background: transparent;
    border: 1.5px solid var(--line);
    border-radius: var(--radius);
    cursor: pointer;
    text-align: center;
    transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}
.don-chip::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--marine);
    transform: translateY(101%);
    transition: transform 260ms cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}
.don-chip:hover::before { transform: translateY(0); }
.don-chip:hover { border-color: var(--marine); }
.don-chip:hover .don-chip__amount,
.don-chip:hover .don-chip__hint { color: white; }

.don-chip.is-active {
    border-color: var(--marine);
}
.don-chip.is-active::before { transform: translateY(0); }
.don-chip.is-active .don-chip__amount,
.don-chip.is-active .don-chip__hint { color: white; }

.don-chip__amount {
    position: relative;
    z-index: 1;
    display: block;
    font-family: var(--display);
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: -0.03em;
    line-height: 1;
    color: var(--marine);
    transition: color 220ms;
}
.don-chip__hint {
    position: relative;
    z-index: 1;
    display: block;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text-muted);
    transition: color 220ms;
}

/* ─── Montant personnalisé ──────────────────────────── */
.don-custom {
    display: flex;
    align-items: baseline;
    gap: 1.5rem;
    padding: 1rem 0;
}
.don-custom__label {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text-muted);
    flex-shrink: 0;
}
.don-custom__field {
    display: flex;
    align-items: baseline;
    gap: 0.75rem;
    flex: 1;
    padding-bottom: 0.75rem;
    border-bottom: 1.5px solid var(--line);
    transition: border-color 200ms;
}
.don-custom__field:focus-within { border-color: var(--blue); }
.don-custom__field input {
    font-family: var(--display);
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: -0.03em;
    color: var(--marine);
    background: transparent;
    border: 0;
    outline: none;
    width: 100%;
    font-variant-numeric: tabular-nums;
}
.don-custom__field input::placeholder {
    color: var(--line);
    font-weight: 400;
}
.don-custom__field .currency {
    font-family: var(--body);
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-muted);
    flex-shrink: 0;
}
.don-custom__field input::-webkit-outer-spin-button,
.don-custom__field input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.don-custom__field input[type=number] { -moz-appearance: textfield; }

/* ─── Cartes de paiement ─────────────────────────────── */
.don-payments {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}
.don-pay {
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1.5rem 1.25rem;
    background: var(--paper);
    border: 1.5px solid var(--line);
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 220ms cubic-bezier(0.4, 0, 0.2, 1);
}
.don-pay:hover { border-color: var(--marine); }
.don-pay.is-active {
    border-color: var(--blue);
    background: var(--blue-soft);
}
.don-pay.is-active::after {
    content: '';
    position: absolute;
    top: -1.5px; right: -1.5px;
    width: 24px; height: 24px;
    background: var(--blue);
    clip-path: polygon(100% 0, 0 0, 100% 100%);
}
.don-pay.is-active::before {
    content: '✓';
    position: absolute;
    top: -1px; right: 1px;
    color: white;
    font-size: 11px;
    font-weight: 700;
    z-index: 2;
}
.don-pay input { position: absolute; opacity: 0; pointer-events: none; }
.don-pay__icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 1.25rem;
    background: var(--paper-tint);
}
.don-pay__name {
    font-family: var(--display);
    font-weight: 600;
    font-size: 0.9375rem;
    color: var(--marine);
    line-height: 1.2;
}
.don-pay__desc {
    font-size: 0.75rem;
    color: var(--text-muted);
    line-height: 1.45;
}

/* ─── Champs formulaire ──────────────────────────────── */
.don-fields {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem 2.5rem;
}
@media (max-width: 640px) {
    .don-fields { grid-template-columns: 1fr; }
    .don-amounts, .don-payments { grid-template-columns: 1fr; }
}
.don-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.don-field--full { grid-column: 1 / -1; }
.don-field label {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--text-muted);
}
.don-field input {
    padding: 0.75rem 0;
    font-family: var(--body);
    font-size: 1.0625rem;
    color: var(--text);
    background: transparent;
    border: 0;
    border-bottom: 1.5px solid var(--line);
    outline: none;
    transition: border-color 200ms;
}
.don-field input:focus { border-color: var(--blue); }

/* ─── Sticky récap ───────────────────────────────────── */
.don-recap {
    position: sticky;
    top: 110px;
    background: var(--paper-tint);
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 2.5rem 2rem;
}
@media (max-width: 1024px) {
    .don-recap { position: static; }
}

.don-recap__label {
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--text-muted);
    margin-bottom: 1rem;
    display: block;
}
.don-recap__amount {
    font-family: var(--display);
    font-weight: 800;
    font-size: clamp(2.25rem, 4vw, 3rem);
    letter-spacing: -0.045em;
    line-height: 1;
    color: var(--marine);
    font-variant-numeric: tabular-nums;
    margin-bottom: 0.75rem;
}
.don-recap__impact {
    font-size: 0.9375rem;
    line-height: 1.5;
    color: var(--text);
    padding-bottom: 1.75rem;
    border-bottom: 1px solid var(--line);
    margin-bottom: 1.75rem;
}
.don-recap__impact strong {
    color: var(--blue);
    font-weight: 700;
}

.don-recap__row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 1rem;
    padding: 0.75rem 0;
    font-size: 0.9375rem;
}
.don-recap__row + .don-recap__row { border-top: 1px solid var(--line-soft); }
.don-recap__row dt { color: var(--text-muted); }
.don-recap__row dd {
    font-weight: 600;
    color: var(--marine);
    text-align: right;
}

.don-recap__trust {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    padding: 1.5rem 0;
    border-top: 1px solid var(--line);
    margin-top: 1rem;
}
.don-recap__trust li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.8125rem;
    color: var(--text-muted);
}
.don-recap__trust svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    color: var(--green);
}

/* ─── Bouton ─────────────────────────────────────────── */
.don-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    width: 100%;
    padding: 1.25rem 1.75rem;
    margin-top: 1.5rem;
    font-family: var(--body);
    font-size: 1rem;
    font-weight: 600;
    color: white;
    background: var(--green);
    border: 0;
    border-radius: var(--radius);
    cursor: pointer;
    transition: background 200ms;
}
.don-btn:hover { background: var(--green-hover); }
.don-btn svg {
    width: 20px;
    height: 20px;
    transition: transform 220ms;
}
.don-btn:hover svg { transform: translateX(4px); }

.don-recap__legal {
    font-size: 0.75rem;
    line-height: 1.55;
    color: var(--text-muted);
    text-align: center;
    margin-top: 1rem;
}

/* ─── Alertes ────────────────────────────────────────── */
.don-alert {
    padding: 1rem 1.25rem;
    border-radius: var(--radius);
    border-left: 3px solid;
    font-size: 0.9375rem;
    margin-bottom: 1.5rem;
    line-height: 1.55;
}
.don-alert--success { background: var(--green-light); border-color: var(--green); color: #143F2C; }
.don-alert--error   { background: #FEF2F2; border-color: #C53030; color: #7F1D1D; }

/* ─── Alternatives ───────────────────────────────────── */
.don-alt {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}
@media (max-width: 640px) { .don-alt { grid-template-columns: 1fr; } }
.don-alt__card {
    padding: 2.5rem 2rem;
    background: white;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    display: block;
    transition: border-color 200ms, transform 200ms;
}
.don-alt__card:hover {
    border-color: var(--marine);
    transform: translateY(-2px);
}
.don-alt__label {
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--blue);
    display: block;
    margin-bottom: 1rem;
}
.don-alt__value {
    font-family: var(--display);
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: -0.02em;
    color: var(--marine);
    margin: 0;
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════
     1. EN-TÊTE ÉDITORIAL
     ═══════════════════════════════════════════════════════ --}}
<section style="padding: 12rem 0 4rem; background: var(--paper);">
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

{{-- ═══════════════════════════════════════════════════════
     2. BANDEAU IMPACT ÉDITORIAL
     ═══════════════════════════════════════════════════════ --}}
<section style="padding: 0 0 6rem;">
    <div class="frame">
        <div class="don-impact">
            <div class="don-impact__figure">
                <img src="{{ asset('images/bibliotheque-enfants.jpg') }}"
                     alt="Enfants dans une bibliothèque scolaire"
                     loading="lazy">
            </div>
            <div>
                <span class="meta" style="color: var(--blue); display:block; margin-bottom: 2rem;">— Votre impact</span>
                <p class="don-impact__quote">
                    « Chaque don finance directement <em>une action de terrain</em> à Maroua — distribution de kits, causeries éducatives, accompagnement des familles. »
                </p>
                <div class="don-impact__stats">
                    <div>
                        <span class="don-impact__stat-value">500+</span>
                        <span class="don-impact__stat-label">Élèves accompagnés</span>
                    </div>
                    <div>
                        <span class="don-impact__stat-value">5</span>
                        <span class="don-impact__stat-label">Quartiers couverts</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     3. FORMULAIRE 2 COLONNES (form + récap sticky)
     ═══════════════════════════════════════════════════════ --}}
<section style="padding: 2rem 0 10rem;">
    <div class="frame">

        @if(session('success'))
            <div class="don-alert don-alert--success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="don-alert don-alert--error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="don-alert don-alert--error">
                <strong>Merci de vérifier ces informations :</strong>
                <ul style="margin: 0.5rem 0 0 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('don.traiter') }}" id="don-form" novalidate>
            @csrf
            <input type="hidden" name="montant" id="montant_hidden" value="5000">
            <input type="hidden" name="moyen" id="moyen_hidden" value="orange">

            <div class="don-layout">

                {{-- ═══ COLONNE FORMULAIRE ═══ --}}
                <div class="don-form-col">

                    {{-- ─── ÉTAPE 1 : Montant ────────────────── --}}
                    <div class="don-step">
                        <div class="don-step__head">
                            <span class="don-step__num">01</span>
                            <h2 class="don-step__title">Choisissez un montant</h2>
                        </div>

                        <div class="don-amounts">
                            <button type="button" class="don-chip" data-amount="5000">
                                <span class="don-chip__amount">5 000 F</span>
                                <span class="don-chip__hint">1 kit scolaire</span>
                            </button>
                            <button type="button" class="don-chip" data-amount="10000">
                                <span class="don-chip__amount">10 000 F</span>
                                <span class="don-chip__hint">2 kits scolaires</span>
                            </button>
                            <button type="button" class="don-chip" data-amount="25000">
                                <span class="don-chip__amount">25 000 F</span>
                                <span class="don-chip__hint">5 kits scolaires</span>
                            </button>
                        </div>

                        <div class="don-custom">
                            <label for="montant_custom" class="don-custom__label">Autre montant</label>
                            <div class="don-custom__field">
                                <input type="number"
                                       id="montant_custom"
                                       min="500"
                                       step="500"
                                       placeholder="15 000"
                                       inputmode="numeric">
                                <span class="currency">FCFA</span>
                            </div>
                        </div>
                    </div>

                    {{-- ─── ÉTAPE 2 : Paiement ────────────────── --}}
                    <div class="don-step">
                        <div class="don-step__head">
                            <span class="don-step__num">02</span>
                            <h2 class="don-step__title">Moyen de paiement</h2>
                        </div>

                        <div class="don-payments">
                            <label class="don-pay" data-payment="orange">
                                <input type="radio" name="moyen_radio" value="orange" checked>
                                <span class="don-pay__icon" style="color: #FF7900;">◉</span>
                                <span class="don-pay__name">Orange Money</span>
                                <span class="don-pay__desc">Paiement mobile, confirmation immédiate</span>
                            </label>

                            <label class="don-pay" data-payment="mtn">
                                <input type="radio" name="moyen_radio" value="mtn">
                                <span class="don-pay__icon" style="color: #FFCC00;">◉</span>
                                <span class="don-pay__name">MTN MoMo</span>
                                <span class="don-pay__desc">Paiement mobile, confirmation immédiate</span>
                            </label>

                            <label class="don-pay" data-payment="carte">
                                <input type="radio" name="moyen_radio" value="carte">
                                <span class="don-pay__icon" style="color: #1A1F71;">◉</span>
                                <span class="don-pay__name">Visa / Mastercard</span>
                                <span class="don-pay__desc">Pour les donateurs à l'étranger</span>
                            </label>
                        </div>
                    </div>

                    {{-- ─── ÉTAPE 3 : Coordonnées ─────────────── --}}
                    <div class="don-step">
                        <div class="don-step__head">
                            <span class="don-step__num">03</span>
                            <h2 class="don-step__title">Vos coordonnées</h2>
                        </div>

                        <div class="don-fields">
                            <div class="don-field">
                                <label for="nom">Nom complet *</label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required autocomplete="name">
                            </div>
                            <div class="don-field">
                                <label for="telephone">Téléphone *</label>
                                <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required autocomplete="tel" placeholder="6XX XXX XXX">
                            </div>
                            <div class="don-field don-field--full">
                                <label for="email">Email (pour le reçu) *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ═══ COLONNE RÉCAP STICKY ═══ --}}
                <aside class="don-recap" aria-label="Récapitulatif du don">
                    <span class="don-recap__label">Votre don</span>
                    <div class="don-recap__amount" id="recap-amount">5 000 FCFA</div>
                    <p class="don-recap__impact" id="recap-impact">
                        Équivaut à <strong>1 kit scolaire</strong> pour un enfant.
                    </p>

                    <dl style="margin: 0;">
                        <div class="don-recap__row">
                            <dt>Moyen</dt>
                            <dd id="recap-moyen">Orange Money</dd>
                        </div>
                        <div class="don-recap__row">
                            <dt>Frais</dt>
                            <dd>Inclus</dd>
                        </div>
                        <div class="don-recap__row">
                            <dt>Reçu</dt>
                            <dd>Par email</dd>
                        </div>
                    </dl>

                    <ul class="don-recap__trust">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Paiement sécurisé via CinetPay
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Chiffrement SSL 256 bits
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Reçu officiel envoyé par email
                        </li>
                    </ul>

                    <button type="submit" class="don-btn">
                        <span>Faire un don</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>

                    <p class="don-recap__legal">
                        Un reçu vous sera envoyé par email dès la confirmation du paiement.
                    </p>
                </aside>

            </div>
        </form>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     4. ALTERNATIVES
     ═══════════════════════════════════════════════════════ --}}
<section style="padding: 6rem 0 8rem; background: var(--paper-tint);">
    <div class="frame-tight">
        <span class="meta" style="display:block; margin-bottom: 2rem; color: var(--blue);">— Autres façons de donner</span>
        <h2 class="display-3" style="margin-bottom: 3rem;">
            Vous préférez donner autrement ?
        </h2>

        <div class="don-alt">
            <a href="tel:+237690856383" class="don-alt__card">
                <span class="don-alt__label">Par téléphone</span>
                <p class="don-alt__value">690 856 383</p>
            </a>
            <a href="https://wa.me/237696506940" target="_blank" rel="noopener" class="don-alt__card">
                <span class="don-alt__label">Par WhatsApp</span>
                <p class="don-alt__value">Discuter →</p>
            </a>
        </div>

        <p class="para" style="margin-top: 3rem; color: var(--text-muted); font-size: 0.9375rem;">
            Pour les dons par virement bancaire ou en nature, contactez-nous directement.
            Chaque don est tracé et fait l'objet d'un reçu officiel.
        </p>
    </div>
</section>

@endsection

@push('scripts')
<script nonce="{{ $cspNonce ?? '' }}">
(function () {
    'use strict';

    const form = document.getElementById('don-form');
    if (!form) return;

    const KIT_UNIT = 5000;
    const MIN_AMOUNT = 500;
    const DEFAULT_AMOUNT = 5000;

    const chips = form.querySelectorAll('.don-chip');
    const customInput = document.getElementById('montant_custom');
    const hiddenAmount = document.getElementById('montant_hidden');
    const hiddenMoyen = document.getElementById('moyen_hidden');
    const paymentCards = form.querySelectorAll('.don-pay');

    const recapAmount = document.getElementById('recap-amount');
    const recapImpact = document.getElementById('recap-impact');
    const recapMoyen = document.getElementById('recap-moyen');

    const MOYENS = {
        orange: 'Orange Money',
        mtn: 'MTN Mobile Money',
        carte: 'Carte bancaire',
    };

    let montant = DEFAULT_AMOUNT;
    let moyen = 'orange';

    function formatNumber(n) {
        return new Intl.NumberFormat('fr-FR').format(n);
    }

    function render() {
        const isCustom = customInput.value !== '';

        // Chips
        chips.forEach(function (chip) {
            const val = parseInt(chip.dataset.amount, 10);
            chip.classList.toggle('is-active', val === montant && !isCustom);
        });

        // Cartes paiement
        paymentCards.forEach(function (card) {
            card.classList.toggle('is-active', card.dataset.payment === moyen);
        });

        // Valeurs cachées
        hiddenAmount.value = montant;
        hiddenMoyen.value = moyen;

        // Récap
        recapAmount.textContent = formatNumber(montant) + ' FCFA';
        recapMoyen.textContent = MOYENS[moyen] || moyen;

        const kits = Math.max(1, Math.floor(montant / KIT_UNIT));
        const label = kits > 1 ? 'kits scolaires' : 'kit scolaire';
        recapImpact.innerHTML = 'Équivaut à <strong>' + kits + ' ' + label + '</strong> pour un enfant.';
    }

    // Clic chips
    chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
            montant = parseInt(chip.dataset.amount, 10);
            customInput.value = '';
            render();
        });
    });

    // Saisie custom — formatage live
    customInput.addEventListener('input', function () {
        const cleaned = customInput.value.replace(/\D/g, '');
        const v = parseInt(cleaned, 10);

        if (!cleaned) {
            montant = DEFAULT_AMOUNT;
        } else if (!isNaN(v) && v >= MIN_AMOUNT) {
            montant = v;
        } else if (!isNaN(v)) {
            montant = MIN_AMOUNT;
        }
        render();
    });

    // Bloque la molette sur le champ custom
    customInput.addEventListener('wheel', function (e) {
        e.preventDefault();
    }, { passive: false });

    // Paiement
    paymentCards.forEach(function (card) {
        card.addEventListener('click', function () {
            moyen = card.dataset.payment;
            const radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            render();
        });
    });

    render();
})();
</script>
@endpush