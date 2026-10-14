<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use App\Notifications\NouveauMotDePasseAdherent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('adherent')->check()) {
            return redirect()->route('adherent.dashboard');
        }

        return view('adherent.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifiant' => ['required', 'string', 'max:255'],
            'password'    => ['required', 'string'],
        ], [
            'identifiant.required' => 'Veuillez saisir votre email ou votre numéro de téléphone.',
            'password.required'    => 'Veuillez saisir votre mot de passe.',
        ]);

        $identifiant = trim($validated['identifiant']);

        // ─── Détection email vs téléphone ─────────────────────
        if (filter_var($identifiant, FILTER_VALIDATE_EMAIL)) {
            // Cas 1 : email → tentative directe
            $credentials = [
                'email'    => $identifiant,
                'password' => $validated['password'],
            ];
        } else {
            // Cas 2 : téléphone → on cherche l'adhérent par plusieurs variantes
            $adherent = $this->trouverParTelephone($identifiant);

            if (! $adherent) {
                throw ValidationException::withMessages([
                    'identifiant' => 'Ces identifiants ne correspondent à aucun compte adhérent.',
                ]);
            }

            // On utilise l'email de l'adhérent pour la tentative d'auth
            $credentials = [
                'email'    => $adherent->email,
                'password' => $validated['password'],
            ];
        }

        if (! Auth::guard('adherent')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'identifiant' => 'Ces identifiants ne correspondent à aucun compte adhérent.',
            ]);
        }

        $adherent = Auth::guard('adherent')->user();

        if (! $adherent->peutAccederEspace()) {
            Auth::guard('adherent')->logout();

            throw ValidationException::withMessages([
                'identifiant' => 'Votre espace adhérent n\'est pas encore activé. Contactez l\'ALIDSC.',
            ]);
        }

        $request->session()->regenerate();
        $adherent->update(['derniere_connexion' => now()]);

        return redirect()->intended(route('adherent.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('adherent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('adherent.login');
    }

    public function showForgotPassword(): View
    {
        return view('adherent.auth.forgot-password');
    }

    public function sendNewPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $adherent = Adherent::where('email', $request->email)->first();

        // Ne jamais révéler si l'email existe ou non (sécurité)
        if (! $adherent || ! $adherent->peutAccederEspace()) {
            return redirect()
                ->route('adherent.login')
                ->with('success', 'Si un compte existe avec cet email, un mot de passe temporaire vous a été envoyé.');
        }

        // Mot de passe lisible : 4 lettres maj + 4 chiffres (ex: ABCD1234)
        $nouveauMdp = Str::upper(Str::random(4)) . random_int(1000, 9999);
        $adherent->update([
            'password'             => Hash::make($nouveauMdp),
            'must_change_password' => true, // ⚠️ Forcé au prochain login
        ]);

        try {
            Notification::route('mail', $adherent->email)
                ->notify(new NouveauMotDePasseAdherent($adherent, $nouveauMdp));
        } catch (\Throwable $e) {
            Log::error('Envoi mot de passe adhérent échoué', [
                'adherent_id' => $adherent->id,
                'exception'   => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('adherent.login')
            ->with('success', 'Un mot de passe temporaire a été envoyé à votre adresse email.');
    }

    /**
     * Cherche un adhérent à partir d'un numéro de téléphone saisi librement.
     *
     * Accepte les formats :
     *   - 690123456
     *   - 6 90 12 34 56
     *   - +237 690 123 456
     *   - 237690123456
     *   - 00237690123456
     *
     * On teste plusieurs variantes en base pour être tolérant aux saisies
     * historiques (l'adhérent a pu saisir avec ou sans indicatif à l'inscription).
     */
    protected function trouverParTelephone(string $input): ?Adherent
    {
        $chiffres = preg_replace('/\D/', '', $input);

        // Retire l'indicatif international éventuel
        if (str_starts_with($chiffres, '00237')) {
            $chiffres = substr($chiffres, 5);
        } elseif (str_starts_with($chiffres, '237')) {
            $chiffres = substr($chiffres, 3);
        }

        // Doit faire 9 chiffres pour un numéro camerounais valide
        if (strlen($chiffres) !== 9) {
            return null;
        }

        // Variantes à chercher en base
        $candidats = array_unique([
            $chiffres,                    // 690123456
            '+237' . $chiffres,           // +237690123456
            '237' . $chiffres,            // 237690123456
            '00237' . $chiffres,          // 00237690123456
            $input,                       // tel que saisi
        ]);

        return Adherent::whereIn('telephone', $candidats)->first();
    }
}