@php
    $user = auth()->user();
    $enabled = $user->hasTwoFactorEnabled();
@endphp

@if($enabled)
    <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 1.25rem; background: #ECFDF5; border-left: 3px solid #059669; margin-bottom: 1.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
            <path d="M20 6 9 17l-5-5"></path>
        </svg>
        <div>
            <p style="font-size: .875rem; color: #065F46; margin: 0; font-weight: 600;">
                La 2FA est activée sur votre compte
            </p>
            <p style="font-size: .8125rem; color: #065F46; margin: .25rem 0 0; opacity: .8;">
                Depuis le {{ $user->two_factor_confirmed_at->translatedFormat('d F Y') }}
            </p>
        </div>
    </div>

    <div style="display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
        <a href="{{ route('2fa.recovery-codes') }}"
           style="padding: .625rem 1.25rem; background: white; color: #10263B; border: 1px solid #E5E0D8; font-size: .875rem; font-weight: 600; border-radius: 4px;">
            Voir les codes de secours
        </a>
    </div>

    <form method="POST" action="{{ route('2fa.disable') }}" style="padding-top: 1.5rem; border-top: 1px solid #E5E0D8;">
        @csrf

        <p style="font-size: .875rem; color: #4A4A4A; margin-bottom: 1rem; line-height: 1.6;">
            Pour désactiver la 2FA, confirmez votre mot de passe.
        </p>

        <div style="display: flex; gap: .75rem;">
            <input type="password" name="password" required placeholder="Mot de passe"
                   style="flex: 1; padding: .625rem .875rem; border: 1px solid #E5E0D8; border-radius: 4px; font-size: .875rem;">
            <button type="submit"
                    onclick="return confirm('Désactiver la 2FA ? Votre compte sera moins sécurisé.')"
                    style="padding: .625rem 1.25rem; background: #DC2626; color: white; border: 0; font-size: .875rem; font-weight: 600; border-radius: 4px; cursor: pointer; white-space: nowrap;">
                Désactiver la 2FA
            </button>
        </div>

        @error('password')
            <p style="margin-top: .5rem; font-size: .8125rem; color: #DC2626;">{{ $message }}</p>
        @enderror
    </form>
@else
    <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem 1.25rem; background: #FFFBEB; border-left: 3px solid #0B6FB8; margin-bottom: 1.5rem;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0B6FB8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <div>
            <p style="font-size: .875rem; color: #713F12; margin: 0; font-weight: 600;">
                La 2FA n'est pas activée
            </p>
            <p style="font-size: .8125rem; color: #713F12; margin: .25rem 0 0; opacity: .8;">
                Nous vous recommandons fortement d'activer l'authentification à deux facteurs pour protéger votre compte administrateur.
            </p>
        </div>
    </div>

    <a href="{{ route('2fa.setup') }}"
       style="display: inline-block; padding: .75rem 1.5rem; background: #10263B; color: white; font-size: .875rem; font-weight: 600; border-radius: 4px;">
        Activer la 2FA
    </a>
@endif
