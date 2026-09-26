<x-guest-layout>
    @section('title', 'Confirmation')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Zone sécurisée
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            Veuillez confirmer votre mot de passe avant de continuer.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label for="password" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Mot de passe
            </label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('password')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer;">
            Confirmer
        </button>
    </form>
</x-guest-layout>