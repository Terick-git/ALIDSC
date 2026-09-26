<x-guest-layout>
    @section('title', 'Mot de passe oublié')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Mot de passe oublié
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            Entrez votre email, nous vous enverrons un lien de réinitialisation.
        </p>
    </div>

    @if (session('status'))
        <div style="padding: .875rem 1rem; background: #ECFDF5; border-left: 3px solid #059669; color: #065F46; font-size: .875rem; margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label for="email" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('email')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer;">
            Envoyer le lien
        </button>

        <p style="text-align: center;">
            <a href="{{ route('login') }}" style="font-size: .875rem; color: #0B6FB8;">
                ← Retour à la connexion
            </a>
        </p>
    </form>
</x-guest-layout>