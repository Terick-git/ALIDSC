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
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('adherent')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte adhérent.',
            ]);
        }

        $adherent = Auth::guard('adherent')->user();

        if (! $adherent->peutAccederEspace()) {
            Auth::guard('adherent')->logout();

            throw ValidationException::withMessages([
                'email' => 'Votre espace adhérent n\'est pas encore activé. Contactez l\'ALIDSC.',
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
        $adherent->update(['password' => Hash::make($nouveauMdp)]);

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
}