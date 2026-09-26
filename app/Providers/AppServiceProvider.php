<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Carbon\Carbon::setLocale('fr');

        $this->configureRateLimiters();
        $this->registerViewComposers();
    }

    /**
     * Configure les rate limiters nommés.
     */
    protected function configureRateLimiters(): void
    {
        // ─── Connexion admin ────────────────────────────────
        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip() . '|' . $request->input('email'))
                ->response(function () {
                    return response()->view('errors.429', [
                        'message' => 'Trop de tentatives de connexion. Réessayez dans une minute.',
                    ], 429);
                });
        });

        // ─── Connexion adhérent ─────────────────────────────
        RateLimiter::for('adherent-login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip() . '|' . $request->input('email'));
        });

        // ─── Mot de passe oublié adhérent ───────────────────
        RateLimiter::for('adherent-password-reset', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perHour(10)->by($request->ip()),
            ];
        });

        // ─── Formulaire de contact ──────────────────────────
        RateLimiter::for('contact', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perHour(15)->by($request->ip()),
                Limit::perDay(50)->by($request->ip()),
            ];
        });

        // ─── Formulaire d'adhésion ──────────────────────────
        RateLimiter::for('adhesion', function (Request $request) {
            return [
                Limit::perMinute(2)->by($request->ip()),
                Limit::perDay(5)->by($request->ip()),
            ];
        });

        // ─── Téléchargement de ressources ───────────────────
        RateLimiter::for('downloads', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });
    }

    /**
     * Enregistre les View Composers.
     */
    protected function registerViewComposers(): void
    {
        View::composer(
            'layouts.admin',
            \App\View\Composers\AdminSidebarComposer::class
        );
    }
}