<x-two-factor-layout>
    @section('title', 'Codes de secours')

    <div style="text-align: center; margin-bottom: 2rem;">
        <h1 style="font-family: 'Bricolage Grotesque', serif; font-size: 1.875rem; font-weight: 400; letter-spacing: -.02em; color: #10263B; margin-bottom: .5rem;">
            Codes de secours
        </h1>
        <p style="font-size: .875rem; color: #8A8A8A; line-height: 1.6;">
            Conservez ces codes en lieu sûr. Chacun ne peut être utilisé qu'une seule fois.
        </p>
    </div>

    @if(session('status'))
        <div style="padding: .875rem 1rem; background: #ECFDF5; border-left: 3px solid #059669; color: #065F46; font-size: .875rem; margin-bottom: 1.5rem;">
            {{ session('status') }}
        </div>
    @endif

    <div style="padding: 1rem 1.25rem; background: #FFFBEB; border-left: 3px solid #0B6FB8; margin-bottom: 1.5rem;">
        <p style="font-size: .8125rem; color: #713F12; margin: 0; line-height: 1.6;">
            <strong>⚠️ Important :</strong> ces codes ne seront plus affichés. Copiez-les maintenant dans votre gestionnaire de mots de passe.
        </p>
    </div>

    <div style="background: #F7F3EC; border: 1px solid #E5E0D8; border-radius: 8px; padding: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; font-family: 'JetBrains Mono', monospace; font-size: .875rem; color: #10263B;">
            @foreach($codes as $code)
                <code style="padding: .5rem .75rem; background: white; border: 1px solid #E5E0D8; border-radius: 4px; text-align: center; letter-spacing: .05em;">
                    {{ $code }}
                </code>
            @endforeach
        </div>
    </div>

    <div style="margin-top: 1.5rem; display: flex; gap: .75rem;">
        <button
            type="button"
            onclick="navigator.clipboard.writeText(`{{ implode("\n", $codes) }}`).then(() => { this.textContent = '✓ Copié'; setTimeout(() => this.textContent = 'Copier tous les codes', 2000); })"
            style="flex: 1; padding: .875rem; background: white; color: #10263B; border: 1px solid #E5E0D8; font-size: .875rem; font-weight: 600; cursor: pointer; border-radius: 4px;">
            Copier tous les codes
        </button>

        <form method="POST" action="{{ route('2fa.recovery-codes.regenerate') }}" style="flex: 1;">
            @csrf
            <button type="submit"
                    onclick="return confirm('Régénérer les codes ? Les anciens seront invalidés.')"
                    style="width: 100%; padding: .875rem; background: white; color: #0B6FB8; border: 1px solid #E5E0D8; font-size: .875rem; font-weight: 600; cursor: pointer; border-radius: 4px;">
                Régénérer
            </button>
        </form>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="{{ route('dashboard') }}"
           style="display: inline-block; padding: .875rem 1.5rem; background: #10263B; color: white; font-size: .875rem; font-weight: 600; border-radius: 4px;">
            Continuer vers le tableau de bord →
        </a>
    </div>
</x-two-factor-layout>