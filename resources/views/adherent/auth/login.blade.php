<x-guest-layout>

    @section('title', 'Connexion — Espace adhérent')

    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Espace adhérent</h1>
        <p style="font-size: 0.875rem; color: #6B7E92;">Connectez-vous à votre compte</p>
    </div>

    @if(session('success'))
        <div class="guest-alert guest-alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="guest-alert guest-alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('adherent.login.post') }}">
        @csrf

        <div class="guest-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="guest-field">
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #22384D;">
                <input type="checkbox" name="remember" style="border-radius: 3px;">
                Se souvenir de moi
            </label>

            <a href="{{ route('adherent.password.forgot') }}" style="color: #0B6FB8;">
                Mot de passe oublié ?
            </a>
        </div>

        <button type="submit" class="guest-btn">Se connecter</button>
    </form>

    <p style="text-align: center; margin-top: 2rem; font-size: 0.875rem; color: #6B7E92;">
        <a href="{{ route('home') }}" style="color: #6B7E92;">← Retour au site</a>
    </p>

</x-guest-layout>