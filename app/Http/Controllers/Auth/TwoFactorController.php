<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function __construct(
        protected TwoFactorService $twoFactor
    ) {}

    // ═══════════════════════════════════════════════════════════
    // CHALLENGE (login)
    // ═══════════════════════════════════════════════════════════

    public function showChallenge(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        if ($request->session()->get('2fa_verified_user_id') === $user->id) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.2fa.challenge');
    }

    public function verifyChallenge(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        $code = preg_replace('/\s+/', '', $request->input('code'));

        // Tentative 1 : code TOTP
        $secret = $user->getTwoFactorSecret();
        if ($secret && $this->twoFactor->verify($secret, $code)) {
            return $this->markVerified($request)
                ->with('success', 'Vérification réussie.');
        }

        // Tentative 2 : code de secours
        $recoveryCodes = $user->getTwoFactorRecoveryCodes();
        if (in_array($code, $recoveryCodes, true)) {
            $recoveryCodes = array_values(array_diff($recoveryCodes, [$code]));
            $user->setTwoFactorRecoveryCodes($recoveryCodes);
            $user->save();

            return $this->markVerified($request)
                ->with('status', 'Code de secours utilisé. Il a été révoqué.');
        }

        throw ValidationException::withMessages([
            'code' => 'Code invalide. Vérifiez votre application d\'authentification.',
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // SETUP (activation)
    // ═══════════════════════════════════════════════════════════

    public function showSetup(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('2fa.recovery-codes');
        }

        // Génère un secret non encore confirmé
        if (! $user->two_factor_secret) {
            $user->setTwoFactorSecret($this->twoFactor->generateSecret());
            $user->save();
        }

        $secret = $user->getTwoFactorSecret();
        $qrCodeUri = $this->twoFactor->getQrCodeUri(
            $secret,
            $user->email,
            config('app.name', 'ALIDSC')
        );

        // Génération locale du QR code (aucun secret n'est envoyé à un tiers)
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(220),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );
        $writer = new \BaconQrCode\Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUri);

        return view('auth.2fa.setup', [
            'secret'     => $secret,
            'qrCodeSvg'  => $qrCodeSvg,
        ]);
    }

    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:10',
        ]);

        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('2fa.recovery-codes');
        }

        $secret = $user->getTwoFactorSecret();
        if (! $secret) {
            return redirect()->route('2fa.setup');
        }

        $code = preg_replace('/\s+/', '', $request->input('code'));

        if (! $this->twoFactor->verify($secret, $code)) {
            throw ValidationException::withMessages([
                'code' => 'Le code est invalide. Vérifiez que votre application est synchronisée.',
            ]);
        }

        // Confirme l'activation + génère les codes de secours
        $recoveryCodes = $this->twoFactor->generateRecoveryCodes(8);

        $user->two_factor_confirmed_at = now();
        $user->setTwoFactorRecoveryCodes($recoveryCodes);
        $user->save();

        // Session marquée vérifiée pour éviter d'être redirigé immédiatement
        $this->markVerified($request);

        return redirect()->route('2fa.recovery-codes')
            ->with('status', '2FA activé. Conservez vos codes de secours en lieu sûr.');
    }

    // ═══════════════════════════════════════════════════════════
    // RECOVERY CODES
    // ═══════════════════════════════════════════════════════════

    public function showRecoveryCodes(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('2fa.setup');
        }

        return view('auth.2fa.recovery-codes', [
            'codes' => $user->getTwoFactorRecoveryCodes(),
        ]);
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('2fa.setup');
        }

        $user->setTwoFactorRecoveryCodes($this->twoFactor->generateRecoveryCodes(8));
        $user->save();

        return redirect()->route('2fa.recovery-codes')
            ->with('status', 'Nouveaux codes de secours générés.');
    }

    // ═══════════════════════════════════════════════════════════
    // DÉSACTIVATION
    // ═══════════════════════════════════════════════════════════

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Mot de passe incorrect.',
            ]);
        }

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        $request->session()->forget(['2fa_verified_user_id', '2fa_verified_at']);

        return redirect()->route('profile.edit')
            ->with('status', '2FA désactivé.');
    }

    // ═══════════════════════════════════════════════════════════
    // UTILITAIRE
    // ═══════════════════════════════════════════════════════════

    protected function markVerified(Request $request): RedirectResponse
    {
        $request->session()->put('2fa_verified_user_id', $request->user()->id);
        $request->session()->put('2fa_verified_at', now()->timestamp);

        $intended = $request->session()->pull('2fa.intended_url');

        return redirect()->to($intended ?: route('dashboard'));
    }
}
