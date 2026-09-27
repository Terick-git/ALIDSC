<x-guest-layout>

    @section('title', 'Mot de passe oublié — Espace adhérent')

    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Mot de passe oublié</h1>
        <p style="font-size: 0.875rem; color: #6B7E92; line-height: 1.6;">
            Saisissez votre email. Nous vous enverrons un mot de passe temporaire à changer dès votre première connexion.
        </p>
    </div>

    @if(session('success'))
        <div class="guest-alert guest-alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('adherent.password.send') }}">
        @csrf

        <div class="guest-field">
            <label for="email">Votre email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="guest-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="guest-btn">Recevoir un mot de passe</button>
    </form>

    <p style="text-align: center; margin-top: 2rem; font-size: 0.875rem;">
        <a href="{{ route('adherent.login') }}" style="color: #6B7E92;">← Retour à la connexion</a>
    </p>

</x-guest-layout>