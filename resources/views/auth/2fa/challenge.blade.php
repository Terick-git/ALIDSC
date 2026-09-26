<x-guest-layout>
    @section('title', 'Vérification en deux étapes')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <div style="width: 56px; height: 56px; margin: 0 auto 1.25rem; border-radius: 50%; background: #F0F4F8; display: flex; align-items: center; justify-content: center;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10263B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Vérification en deux étapes
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            Saisissez le code à 6 chiffres généré par votre application d'authentification.
        </p>
    </div>

    @if(session('status'))
        <div style="padding: .875rem 1rem; background: #ECFDF5; border-left: 3px solid #059669; color: #065F46; font-size: .875rem; margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.challenge.verify') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label for="code" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Code de vérification
            </label>
            <input
                id="code"
                type="text"
                name="code"
                inputmode="text"
                autocomplete="one-time-code"
                autofocus
                required
                maxlength="20"
                placeholder="123456"
                style="width: 100%; padding: 1rem 0; font-family: 'JetBrains Mono', 'Fira Code', monospace; font-size: 1.5rem; letter-spacing: .35em; text-align: center; color: #1A1A1A; background: transparent; border: 0; border-bottom: 2px solid #E5E0D8; outline: none; transition: border-color 200ms;"
                onfocus="this.style.borderColor='#0B6FB8'"
                onblur="this.style.borderColor='#E5E0D8'">
            @error('code')
                <p style="margin-top: .75rem; font-size: .8125rem; color: #C53030; text-align: center;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer; transition: background 200ms;"
                onmouseover="this.style.background='#0A1B2A'"
                onmouseout="this.style.background='#10263B'">
            Vérifier
        </button>
    </form>

    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #E5E0D8; text-align: center;">
        <p style="font-size: .8125rem; color: #8A8A8A; margin-bottom: .75rem;">
            Vous avez perdu votre appareil ?
        </p>
        <p style="font-size: .8125rem; color: #8A8A8A;">
            Utilisez un <strong style="color: #4A4A4A;">code de secours</strong> à la place du code à 6 chiffres.
        </p>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="font-size: .8125rem; color: #8A8A8A; background: none; border: 0; cursor: pointer; text-decoration: underline;">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
