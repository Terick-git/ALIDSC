<footer class="colophon">
    <div class="frame">

        <div class="colophon-brand">
            <img src="{{ asset('images/logo-alidsc.jpg') }}" alt="ALIDSC">
            <div class="colophon-brand-text">
                ALIDSC
                <small>Étudiez. Rêvez. Réussissez.</small>
            </div>
        </div>

        <div class="colophon-grid">
            <div>
                <h4>L'association</h4>
                <p style="font-size:0.9375rem; line-height:1.65; max-width: 380px;">
                    Association de Lutte contre les Inégalités et les Décrochages Scolaires
                    au Cameroun. Agit depuis 2022 dans la région de l'Extrême-Nord.
                </p>
                <p style="font-size:0.875rem; margin-top: 1rem; color: rgba(255,255,255,0.55);">
                    <strong style="color: rgba(255,255,255,0.75);">Horaires :</strong>
                    Lun–Ven 8h–18h · Sam 9h–13h
                </p>
            </div>

            <div>
                <h4>Découvrir</h4>
                <div class="colophon-links">
                    <a href="{{ route('a-propos') }}">Qui sommes-nous</a>
                    <a href="{{ route('actions') }}">Nos actions</a>
                    <a href="{{ route('impact') }}">Notre impact</a>
                    <a href="{{ route('actualites') }}">Actualités</a>
                </div>
            </div>

            <div>
                <h4>Agir</h4>
                <div class="colophon-links">
                    <a href="{{ route('adherer') }}">Devenir adhérent</a>
                    <a href="{{ route('don') }}">Faire un don</a>
                    <a href="{{ route('contact') }}">Nous contacter</a>
                    <a href="https://wa.me/237696506940" target="_blank" rel="noopener" style="color: #4ADE80; font-weight: 600;">
                        WhatsApp direct →
                    </a>
                </div>
            </div>

            <div>
                <h4>Contact</h4>
                <div class="colophon-links">
                    <span>Pitoire (derrière la CNPS)</span>
                    <span>Maroua, Extrême-Nord</span>
                    <a href="tel:+237690856383">690 856 383</a>
                    <a href="mailto:contact@alidsc.org">contact@alidsc.org</a>
                </div>
            </div>
        </div>

        <div class="colophon-foot">
            <span>© {{ date('Y') }} ALIDSC — Tous droits réservés</span>
            <span style="display:flex; gap:1.25rem; flex-wrap:wrap;">
                <a href="{{ route('legal.mentions') }}">Mentions légales</a>
                <a href="{{ route('legal.confidentialite') }}">Confidentialité</a>
            </span>
        </div>

    </div>
</footer>