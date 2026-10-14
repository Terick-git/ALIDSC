<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    protected const SESSION_TTL = 120; // minutes

    public function handle(Request $request, Closure $next): Response
    {
        // ─────────────────────────────────────────────────────────────
        // Bypass 2FA en environnement de test.
        // Voir phpunit.xml (SKIP_2FA_ENFORCEMENT=true).
        //
        // TwoFactorAuthTest force ce flag à false dans son setUp()
        // pour tester le comportement réel du middleware.
        //
        // ⚠️ En production, ce flag est false par défaut : la 2FA reste
        //    obligatoire pour tous les admins.
        // ─────────────────────────────────────────────────────────────
        if (config('app.skip_2fa_enforcement')) {
            return $next($request);
        }

        $user = $request->user();

        // 1. Aucun utilisateur connecté → laisse passer
        if (! $user) {
            return $next($request);
        }

        // 2. La 2FA ne s'applique qu'aux admins (User)
        if (! $user instanceof User) {
            return $next($request);
        }

        // 3. Routes 2FA et logout → toujours accessibles (évite les boucles)
        if ($request->routeIs('2fa.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // 4. Admin sans 2FA → doit l'activer immédiatement
        if (! $user->hasTwoFactorEnabled()) {
            if ($user->isAdmin()) {
                return redirect()
                    ->route('2fa.setup')
                    ->with('warning', 'La 2FA est obligatoire pour tous les administrateurs. Activez-la pour continuer.');
            }
            return $next($request);
        }

        // 5. Session 2FA déjà validée et non expirée
        $sessionUserId    = $request->session()->get('2fa_verified_user_id');
        $sessionTimestamp = $request->session()->get('2fa_verified_at', 0);
        $expired          = (now()->timestamp - $sessionTimestamp) > (self::SESSION_TTL * 60);

        if ($sessionUserId === $user->id && ! $expired) {
            return $next($request);
        }

        // 6. Redirection vers le challenge
        if (! $request->session()->has('2fa.intended_url')) {
            $request->session()->put('2fa.intended_url', $request->fullUrl());
        }

        return redirect()->route('2fa.challenge');
    }
}