<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActualiteController;
use App\Http\Controllers\ActionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdhesionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\GalerieController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| SITE PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [ActualiteController::class, 'home'])->name('home');

Route::get('/actualites', [ActualiteController::class, 'index'])->name('actualites');
Route::get('/actualites/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');

Route::get('/nos-actions', [ActionController::class, 'index'])->name('actions');
Route::get('/nos-actions/{slug}', [ActionController::class, 'show'])->name('actions.show');

Route::view('/a-propos', 'pages.a-propos')->name('a-propos');
Route::view('/nos-objectifs', 'pages.objectifs')->name('objectifs');

// ─── Don ─────────────────────────────────────────────
Route::view('/don', 'pages.don')->name('don');

Route::post('/don', [\App\Http\Controllers\DonationController::class, 'traiter'])
    ->middleware('throttle:donation')
    ->name('don.traiter');

// ⚠️ URL signée : impossible de deviner la référence
Route::get('/don/merci/{reference}', [\App\Http\Controllers\DonationController::class, 'merci'])
    ->middleware('signed')
    ->name('don.merci');

Route::post('/don/webhook', [\App\Http\Controllers\DonationController::class, 'webhook'])
    ->name('don.webhook')
    ->withoutMiddleware([\App\Http\Middleware\EnsureTwoFactorVerified::class]);

// Reçu PDF — protégé par une URL signée (30 jours, généré depuis l'email)
Route::get('/don/recu/{reference}', [\App\Http\Controllers\DonationController::class, 'recu'])
    ->middleware('signed')
    ->name('don.recu');

Route::get('/notre-impact', [ImpactController::class, 'index'])->name('impact');

Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements');
Route::get('/evenements/{slug}', [EvenementController::class, 'show'])->name('evenements.show');

Route::get('/partenaires', [PartenaireController::class, 'index'])->name('partenaires');

Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources');
Route::get('/ressources/{slug}', [RessourceController::class, 'show'])->name('ressources.show');
Route::get('/ressources/{slug}/telecharger', [RessourceController::class, 'telecharger'])
    ->middleware('throttle:downloads')
    ->name('ressources.telecharger');

Route::get('/galerie', [GalerieController::class, 'index'])->name('galerie');
Route::get('/galerie/{slug}', [GalerieController::class, 'show'])->name('galerie.show');

Route::get('/adherer', [AdhesionController::class, 'index'])->name('adherer');
Route::post('/adherer', [AdhesionController::class, 'store'])
    ->middleware('throttle:adhesion')
    ->name('adherer.store');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

Route::view('/politique-de-confidentialite', 'legal.politique-confidentialite')->name('legal.confidentialite');
Route::view('/mentions-legales', 'legal.mentions-legales')->name('legal.mentions');

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFIL UTILISATEUR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('actualites', \App\Http\Controllers\Admin\ActualiteController::class)
        ->except(['show']);

    Route::resource('actions', \App\Http\Controllers\Admin\ActionController::class)
        ->except(['show']);

    Route::resource('partenaires', \App\Http\Controllers\Admin\PartenaireController::class)
        ->except(['show']);

    Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class)
        ->except(['show']);

    Route::resource('temoignages', \App\Http\Controllers\Admin\TemoignageController::class)
        ->except(['show']);

    Route::resource('evenements', \App\Http\Controllers\Admin\EvenementController::class)
        ->except(['show']);

    Route::resource('ressources', \App\Http\Controllers\Admin\RessourceController::class)
        ->except(['show']);

    Route::resource('albums', \App\Http\Controllers\Admin\AlbumController::class);
    Route::post('albums/{album}/photos', [\App\Http\Controllers\Admin\AlbumController::class, 'ajouterPhotos'])
        ->name('albums.ajouter-photos');
    Route::patch('photos/{photo}', [\App\Http\Controllers\Admin\AlbumController::class, 'majPhoto'])
        ->name('photos.maj');
    Route::delete('photos/{photo}', [\App\Http\Controllers\Admin\AlbumController::class, 'supprimerPhoto'])
        ->name('photos.supprimer');

    Route::resource('adherents', \App\Http\Controllers\Admin\AdherentController::class)
        ->except(['create', 'store']);
    Route::post('adherents/{adherent}/payer', [\App\Http\Controllers\Admin\AdherentController::class, 'payer'])
        ->name('adherents.payer');
    Route::post('adherents/{adherent}/accepter', [\App\Http\Controllers\Admin\AdherentController::class, 'accepter'])
        ->name('adherents.accepter');
    Route::post('adherents/{adherent}/refuser', [\App\Http\Controllers\Admin\AdherentController::class, 'refuser'])
        ->name('adherents.refuser');
    Route::post('adherents/{adherent}/activer', [\App\Http\Controllers\Admin\AdherentController::class, 'activer'])
        ->name('adherents.activer');
    Route::get('adherents/{adherent}/piece-identite', [\App\Http\Controllers\Admin\AdherentController::class, 'telechargerPiece'])
        ->name('adherents.piece');

    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)
        ->only(['index', 'show', 'destroy']);

    Route::get('export/adherents', [\App\Http\Controllers\Admin\ExportController::class, 'adherents'])
        ->name('export.adherents');
    Route::get('export/messages', [\App\Http\Controllers\Admin\ExportController::class, 'messages'])
        ->name('export.messages');

    Route::get('sidebar-counts', [\App\Http\Controllers\Admin\SidebarController::class, 'counts'])
        ->name('sidebar-counts');
});

/*
|--------------------------------------------------------------------------
| ESPACE ADHÉRENT
|--------------------------------------------------------------------------
*/

Route::prefix('adherent')->name('adherent.')->group(function () {

    Route::get('login', [\App\Http\Controllers\Adherent\AuthController::class, 'showLogin'])
        ->name('login');
    Route::post('login', [\App\Http\Controllers\Adherent\AuthController::class, 'login'])
        ->middleware('throttle:adherent-login')
        ->name('login.post');

    Route::post('logout', [\App\Http\Controllers\Adherent\AuthController::class, 'logout'])
        ->name('logout');

    Route::get('mot-de-passe-oublie', [\App\Http\Controllers\Adherent\AuthController::class, 'showForgotPassword'])
        ->name('password.forgot');
    Route::post('mot-de-passe-oublie', [\App\Http\Controllers\Adherent\AuthController::class, 'sendNewPassword'])
        ->middleware('throttle:adherent-password-reset')
        ->name('password.send');

    // Zone protégée : adhérent + mot de passe à changer obligatoirement
    Route::middleware(['adherent', \App\Http\Middleware\EnsurePasswordChanged::class])->group(function () {

        Route::get('dashboard', [\App\Http\Controllers\Adherent\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('profil', [\App\Http\Controllers\Adherent\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('profil', [\App\Http\Controllers\Adherent\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::patch('profil/mot-de-passe', [\App\Http\Controllers\Adherent\ProfileController::class, 'updatePassword'])
            ->name('profile.password');

        Route::get('documents', [\App\Http\Controllers\Adherent\DocumentController::class, 'index'])
            ->name('documents');
        Route::get('documents/{document}/telecharger', [\App\Http\Controllers\Adherent\DocumentController::class, 'download'])
            ->name('documents.download');

        Route::get('actualites', [\App\Http\Controllers\Adherent\ActualiteController::class, 'index'])
            ->name('actualites');
        Route::get('actualites/{actualite}', [\App\Http\Controllers\Adherent\ActualiteController::class, 'show'])
            ->name('actualites.show');

                // Cotisation
        Route::get('cotisation', [\App\Http\Controllers\Adherent\CotisationController::class, 'index'])
            ->name('cotisation');
        Route::get('cotisation/recu/{paiement}', [\App\Http\Controllers\Adherent\CotisationController::class, 'recu'])
            ->name('cotisation.recu');
    });
});

require __DIR__.'/auth.php';
