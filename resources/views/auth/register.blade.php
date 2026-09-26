<x-guest-layout>
    @section('title', 'Inscription')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Créer un compte
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A;">Rejoignez l'équipe ALIDSC</p>
    </div>

    <form method="POST" action="{{ route('register') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        <div>
            <label for="name" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Nom complet
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('name')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('email')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Mot de passe
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('password')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Confirmer le mot de passe
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('login') }}" style="font-size: .875rem; color: #0B6FB8;">
                Déjà inscrit ?
            </a>
            <button type="submit"
                    style="padding: 1rem 2rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer;">
                S'inscrire
            </button>
        </div>
    </form>
</x-guest-layout>