<x-guest-layout>
    @section('title', 'Vérification email')

    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Vérifiez votre email
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            Merci de votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous vous avons envoyé.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="padding: .875rem 1rem; background: #ECFDF5; border-left: 3px solid #059669; color: #065F46; font-size: .875rem; margin-bottom: 1.5rem;">
            Un nouveau lien de vérification a été envoyé à votre adresse email.
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    style="width: 100%; padding: 1rem; background: #10263B; color: white; border: 0; font-family: inherit; font-size: .9375rem; font-weight: 600; cursor: pointer;">
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width: 100%; padding: 1rem; background: transparent; color: #8A8A8A; border: 1px solid #E5E0D8; font-family: inherit; font-size: .875rem; cursor: pointer;">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>