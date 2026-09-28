<x-two-factor-layout>
    @section('title', 'Activer la 2FA')

    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Activer la 2FA
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            1. Scannez le QR code avec Google Authenticator, Authy, 1Password ou toute autre application TOTP.<br>
            2. Saisissez le code généré pour confirmer.
        </p>
    </div>

    {{-- Étape 1 : QR code (généré localement, aucun tiers) + secret manuel --}}
    <div style="background: #F7F3EC; border: 1px solid #E5E0D8; border-radius: 8px; padding: 2rem; text-align: center; margin-bottom: 2rem;">

        <div style="background: white; padding: 1rem; border-radius: 6px; display: inline-block; margin-bottom: 1.5rem; width: 220px; height: 220px;">
            {{-- $qrCodeSvg est injecté par TwoFactorController@showSetup --}}
            {{-- C'est un SVG inline (pas d'image externe, aucun secret transmis à un tiers) --}}
            {!! $qrCodeSvg !!}
        </div>

        <p style="font-size: .75rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .75rem;">
            Ou saisissez la clé manuellement
        </p>
        <code style="display: inline-block; padding: .75rem 1rem; background: white; border: 1px solid #E5E0D8; border-radius: 4px; font-family: 'JetBrains Mono', monospace; font-size: .8125rem; letter-spacing: .1em; color: #10263B; word-break: break-all; max-width: 100%;">
            {{ $secret }}
        </code>
    </div>

    {{-- Étape 2 : Confirmation --}}
    <form method="POST" action="{{ route('2fa.setup.enable') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label for="code" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Code à 6 chiffres
            </label>
            <input
                id="code"
                type="text"
                name="code"
                inputmode="numeric"
                autocomplete="one-time-code"
                autofocus
                required
                maxlength="6"
                pattern="[0-9]{6}"
                placeholder="000000"
                style="width: 100%; padding: 1rem 0; font-family: 'JetBrains Mono', monospace; font-size: 1.5rem; letter-spacing: .35em; text-align: center; color: #1A1A1A; background: transparent; border: 0; border-bottom: 2px solid #E5E0D8; outline: none;">
            @error('code')
                <p style="margin-top: .75rem; font-size: .8125rem; color: #C53030; text-align: center;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-size: .9375rem; font-weight: 600; cursor: pointer;">
            Activer la 2FA
        </button>
    </form>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="{{ route('profile.edit') }}" style="font-size: .8125rem; color: #8A8A8A;">
            ← Annuler
        </a>
    </div>
</x-two-factor-layout>