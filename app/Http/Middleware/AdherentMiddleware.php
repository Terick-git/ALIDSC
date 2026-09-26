<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdherentMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('adherent')->check()) {
            return redirect()->route('adherent.login');
        }

        if (!Auth::guard('adherent')->user()->peutAccederEspace()) {
            Auth::guard('adherent')->logout();
            return redirect()->route('adherent.login')
                ->with('error', 'Votre espace adhérent n\'est pas encore activé.');
        }

        return $next($request);
    }
}
