<x-guest-layout>
    @section('title', 'Connexion')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Connexion
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A;">Accédez à votre espace administrateur</p>
    </div>

    @if (session('status'))
        <div style="padding: .875rem 1rem; background: #ECFDF5; border-left: 3px solid #059669; color: #065F46; font-size: .875rem; margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('email')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        {{-- Mot de passe --}}
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

        {{-- Options --}}
        <div style="display: flex; align-items: center; justify-content: space-between; font-size: .875rem;">
            <label style="display: flex; align-items: center; gap: .5rem; cursor: pointer;">
                <input type="checkbox" name="remember" style="border-radius: 3px;">
                <span style="color: #4A4A4A;">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="color: #0B6FB8;">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit"
                style="margin-top: .5rem; width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer; transition: background 200ms;">
            Se connecter
        </button>

        {{-- Lien inscription --}}
        @if (Route::has('register'))
            <p style="text-align: center; font-size: .875rem; color: #8A8A8A; margin-top: 1rem;">
                Pas encore de compte ?
                <a href="{{ route('register') }}" style="color: #0B6FB8;">S'inscrire</a>
            </p>
        @endif
    </form>
</x-guest-layout>