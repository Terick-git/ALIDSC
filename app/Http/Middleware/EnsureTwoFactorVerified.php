<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Durée de validité de la session 2FA (en minutes).
     */
    protected const SESSION_TTL = 120; // 2 heures

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Aucun utilisateur connecté → laisser passer (middleware `auth` gère)
        if (! $user) {
            return $next($request);
        }

        // ═══════════════════════════════════════════════════════════
        // 2. IMPORTANT : la 2FA ne s'applique qu'aux admins (User)
        //    Les adhérents (Adherent) n'ont pas de 2FA → on les laisse passer.
        // ═══════════════════════════════════════════════════════════
        if (! $user instanceof User) {
            return $next($request);
        }

        // 3. 2FA non activé sur ce compte → pas de challenge
        if (! $user->hasTwoFactorEnabled()) {
            return $next($request);
        }

        // 4. Routes 2FA et logout → ne pas rediriger (évite les boucles)
        if ($request->routeIs('2fa.*') || $request->routeIs('logout')) {
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