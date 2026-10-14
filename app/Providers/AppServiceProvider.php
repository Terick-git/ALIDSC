<?php

namespace App\Providers;

use App\Models\Adherent;
use App\Models\Message;
use App\View\Composers\AdminSidebarComposer;
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
        $this->registerCacheInvalidation();
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

        // ─── Connexion adhérent (email OU téléphone) ────────
        RateLimiter::for('adherent-login', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip() . '|' . $request->input('identifiant', ''))
                ->response(function () {
                    return response()->view('errors.429', [
                        'message' => 'Trop de tentatives de connexion. Réessayez dans une minute.',
                    ], 429);
                });
        });

        // ─── Mot de passe oublié adhérent ───────────────────
        RateLimiter::for('adherent-password-reset', function (Request $request) {
            return [
                Limit::perMinute(3)
                    ->by($request->ip())
                    ->response(function () {
                        return response()->view('errors.429', [
                            'message' => 'Trop de demandes de réinitialisation. Patientez quelques minutes.',
                        ], 429);
                    }),
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

        // ─── Formulaire de don ──────────────────────────────
        RateLimiter::for('donation', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(20)->by($request->ip()),
            ];
        });
    }

    /**
     * Enregistre les View Composers.
     */
    protected function registerViewComposers(): void
    {
        View::composer(
            'layouts.admin',
            AdminSidebarComposer::class
        );
    }

    /**
     * Invalide le cache des compteurs de la sidebar admin dès qu'un
     * Message ou un Adhérent est créé / modifié / supprimé.
     */
    protected function registerCacheInvalidation(): void
    {
        $bust = fn () => AdminSidebarComposer::forgetCache();

        Message::created($bust);
        Message::updated($bust);
        Message::deleted($bust);

        Adherent::created($bust);
        Adherent::updated($bust);
        Adherent::deleted($bust);
    }
}