<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    protected string $nonce;

    public function __construct()
    {
        $this->nonce = Str::random(32);
    }

    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('csp_nonce', $this->nonce);
        view()->share('cspNonce', $this->nonce);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        if ($request->isSecure() && !app()->isLocal()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        $response->headers->set('Content-Security-Policy', $this->buildCsp());

        if ($request->is('admin/*') || $request->is('dashboard') || $request->is('profile*')) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
        }

        return $response;
    }

    protected function buildCsp(): string
    {
        $isDev = app()->isLocal();

        // ─────────────────────────────────────────────────────────
        // En développement : Vite HMR + eval inline.
        // En production : nonce + 'unsafe-inline' TEMPORAIRE.
        //
        // ⚠️  TODO (dans les 2 semaines) : retirer 'unsafe-inline' dès que
        //     tous les onclick= et <script> inline auront été convertis
        //     (voir les fichiers admin/adherents/profile/2fa).
        //     Objectif final : 'self' 'nonce-xxx' uniquement.
        // ─────────────────────────────────────────────────────────
        $scriptSrc = $isDev
            ? "'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 http://127.0.0.1:5173 ws://localhost:5173 ws://127.0.0.1:5173"
            : "'self' 'nonce-{$this->nonce}' 'unsafe-inline'";

        $connectSrc = $isDev
            ? "'self' ws://localhost:5173 ws://127.0.0.1:5173 http://localhost:5173 http://127.0.0.1:5173"
            : "'self'";

        $directives = [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "script-src-elem {$scriptSrc}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: blob: https:",
            "connect-src {$connectSrc}",
            "media-src 'self'",
            "object-src 'none'",
            "frame-src 'none'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "worker-src 'self' blob:",
            "manifest-src 'self'",
            "upgrade-insecure-requests",
        ];

        if ($isDev) {
            $directives = array_filter($directives, fn ($d) => $d !== 'upgrade-insecure-requests');
        }

        return implode('; ', $directives);
    }
}