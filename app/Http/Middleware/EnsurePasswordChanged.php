<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $adherent = Auth::guard('adherent')->user();

        // Aucun adhérent connecté → laisse passer (le middleware `adherent` gère)
        if (! $adherent) {
            return $next($request);
        }

        // Pas de changement forcé → laisse passer
        if (! $adherent->must_change_password) {
            return $next($request);
        }

        // Routes autorisées malgré le changement forcé :
        //   - page profil (pour changer le mot de passe)
        //   - déconnexion
        if ($request->routeIs('adherent.profile.*') || $request->routeIs('adherent.logout')) {
            return $next($request);
        }

        return redirect()
            ->route('adherent.profile.edit')
            ->with('warning', 'Vous devez changer votre mot de passe temporaire avant de continuer.');
    }
}