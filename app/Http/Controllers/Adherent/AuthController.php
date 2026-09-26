<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('adherent')->check()) {
            return redirect()->route('adherent.dashboard');
        }
        return view('adherent.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('adherent')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte adhérent.',
            ]);
        }

        $adherent = Auth::guard('adherent')->user();

        if (!$adherent->peutAccederEspace()) {
            Auth::guard('adherent')->logout();
            throw ValidationException::withMessages([
                'email' => 'Votre espace adhérent n\'est pas encore activé. Contactez l\'ALIDSC.',
            ]);
        }

        $request->session()->regenerate();
        $adherent->update(['derniere_connexion' => now()]);

        return redirect()->intended(route('adherent.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('adherent')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('adherent.login');
    }

    // Mot de passe oublié : on génère un mot de passe temporaire et on l'envoie
    public function showForgotPassword()
    {
        return view('adherent.auth.forgot-password');
    }

    public function sendNewPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $adherent = \App\Models\Adherent::where('email', $request->email)->first();

        if (!$adherent || !$adherent->peutAccederEspace()) {
            // On ne dit pas si l'email existe ou pas (sécurité)
            return redirect()->route('adherent.login')
                ->with('success', 'Si un compte existe avec cet email, un mot de passe temporaire vous a été envoyé.');
        }

        // Génère un mot de passe aléatoire lisible
        $nouveauMdp = Str::upper(Str::random(4)) . rand(1000, 9999);
        $adherent->update(['password' => Hash::make($nouveauMdp)]);

        // Envoie par email
        try {
            \Illuminate\Support\Facades\Notification::route('mail', $adherent->email)
                ->notify(new \App\Notifications\NouveauMotDePasseAdherent($adherent, $nouveauMdp));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi mdp : ' . $e->getMessage());
        }

        return redirect()->route('adherent.login')
            ->with('success', 'Un mot de passe temporaire a été envoyé à votre adresse email.');
    }
}