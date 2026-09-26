<x-guest-layout>
    @section('title', 'Réinitialiser le mot de passe')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Nouveau mot de passe
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A;">Choisissez un mot de passe sécurisé</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" style="display: flex; flex-direction: column; gap: 1.5rem;">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('email')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Nouveau mot de passe
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('password')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" style="display: block; font-size: .6875rem; font-weight: 500; letter-spacing: .15em; text-transform: uppercase; color: #8A8A8A; margin-bottom: .5rem;">
                Confirmer
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   style="width: 100%; padding: .75rem 0; font-family: inherit; font-size: 1rem; color: #1A1A1A; background: transparent; border: 0; border-bottom: 1px solid #E5E0D8; outline: none;">
            @error('password_confirmation')
                <p style="margin-top: .5rem; font-size: .8125rem; color: #C53030;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer;">
            Réinitialiser le mot de passe
        </button>
    </form>
</x-guest-layout>