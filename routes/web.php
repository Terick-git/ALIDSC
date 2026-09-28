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

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Accueil
Route::get('/', [ActualiteController::class, 'home'])->name('home');

// Actualités
Route::get('/actualites', [ActualiteController::class, 'index'])->name('actualites');
Route::get('/actualites/{slug}', [ActualiteController::class, 'show'])->name('actualites.show');

// Actions
Route::get('/nos-actions', [ActionController::class, 'index'])->name('actions');
Route::get('/nos-actions/{slug}', [ActionController::class, 'show'])->name('actions.show');

// Pages statiques
Route::view('/a-propos', 'pages.a-propos')->name('a-propos');
Route::view('/nos-objectifs', 'pages.objectifs')->name('objectifs');
Route::view('/don', 'pages.don')->name('don');

// Impact
Route::get('/notre-impact', [ImpactController::class, 'index'])->name('impact');

// Événements
Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements');
Route::get('/evenements/{slug}', [EvenementController::class, 'show'])->name('evenements.show');

// Partenaires
Route::get('/partenaires', [PartenaireController::class, 'index'])->name('partenaires');

// Ressources
Route::get('/ressources', [RessourceController::class, 'index'])->name('ressources');
Route::get('/ressources/{slug}', [RessourceController::class, 'show'])->name('ressources.show');
Route::get('/ressources/{slug}/telecharger', [RessourceController::class, 'telecharger'])
    ->middleware('throttle:downloads')
    ->name('ressources.telecharger');

// Galerie
Route::get('/galerie', [GalerieController::class, 'index'])->name('galerie');
Route::get('/galerie/{slug}', [GalerieController::class, 'show'])->name('galerie.show');

// Adhésion
Route::get('/adherer', [AdhesionController::class, 'index'])->name('adherer');
Route::post('/adherer', [AdhesionController::class, 'store'])
    ->middleware('throttle:adhesion')
    ->name('adherer.store');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('contact.store');

// Pages légales
Route::view('/politique-de-confidentialite', 'legal.politique-confidentialite')->name('legal.confidentialite');
Route::view('/mentions-legales', 'legal.mentions-legales')->name('legal.mentions');

/*
|--------------------------------------------------------------------------
| DASHBOARD ADMIN — protégé par le middleware `admin`
|--------------------------------------------------------------------------
| IMPORTANT : le middleware `admin` est obligatoire.
| Sans lui, tout utilisateur inscrit (rôle « membre » par défaut)
| accède au tableau de bord et voit les statistiques internes
| (recettes, adhérents en attente, derniers messages…).
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

    // Actualités
    Route::resource('actualites', \App\Http\Controllers\Admin\ActualiteController::class)
        ->except(['show']);

    // Actions
    Route::resource('actions', \App\Http\Controllers\Admin\ActionController::class)
        ->except(['show']);

    // Partenaires
    Route::resource('partenaires', \App\Http\Controllers\Admin\PartenaireController::class)
        ->except(['show']);

    // Documents
    Route::resource('documents', \App\Http\Controllers\Admin\DocumentController::class)
        ->except(['show']);

    // Témoignages
    Route::resource('temoignages', \App\Http\Controllers\Admin\TemoignageController::class)
        ->except(['show']);

    // Événements
    Route::resource('evenements', \App\Http\Controllers\Admin\EvenementController::class)
        ->except(['show']);

    // Ressources
    Route::resource('ressources', \App\Http\Controllers\Admin\RessourceController::class)
        ->except(['show']);

    // Albums photos
    Route::resource('albums', \App\Http\Controllers\Admin\AlbumController::class);
    Route::post('albums/{album}/photos', [\App\Http\Controllers\Admin\AlbumController::class, 'ajouterPhotos'])
        ->name('albums.ajouter-photos');
    Route::patch('photos/{photo}', [\App\Http\Controllers\Admin\AlbumController::class, 'majPhoto'])
        ->name('photos.maj');
    Route::delete('photos/{photo}', [\App\Http\Controllers\Admin\AlbumController::class, 'supprimerPhoto'])
        ->name('photos.supprimer');

    // Adhérents
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

    // Messages
    Route::resource('messages', \App\Http\Controllers\Admin\MessageController::class)
        ->only(['index', 'show', 'destroy']);

    // Exports
    Route::get('export/adherents', [\App\Http\Controllers\Admin\ExportController::class, 'adherents'])
        ->name('export.adherents');
    Route::get('export/messages', [\App\Http\Controllers\Admin\ExportController::class, 'messages'])
        ->name('export.messages');

    // Sidebar counts (alimente les badges Alpine sur le dashboard admin)
    Route::get('sidebar-counts', [\App\Http\Controllers\Admin\SidebarController::class, 'counts'])
        ->name('sidebar-counts');
});

/*
|--------------------------------------------------------------------------
| ESPACE ADHÉRENT
|--------------------------------------------------------------------------
*/

Route::prefix('adherent')->name('adherent.')->group(function () {

    // Auth adhérent
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

    // Zone protégée
    Route::middleware('adherent')->group(function () {

        Route::get('dashboard', [\App\Http\Controllers\Adherent\DashboardController::class, 'index'])
            ->name('dashboard');

        // Profil
        Route::get('profil', [\App\Http\Controllers\Adherent\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('profil', [\App\Http\Controllers\Adherent\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::patch('profil/mot-de-passe', [\App\Http\Controllers\Adherent\ProfileController::class, 'updatePassword'])
            ->name('profile.password');

        // Documents
        Route::get('documents', [\App\Http\Controllers\Adherent\DocumentController::class, 'index'])
            ->name('documents');
        Route::get('documents/{document}/telecharger', [\App\Http\Controllers\Adherent\DocumentController::class, 'download'])
            ->name('documents.download');

        // Actualités internes
        Route::get('actualites', [\App\Http\Controllers\Adherent\ActualiteController::class, 'index'])
            ->name('actualites');
        Route::get('actualites/{actualite}', [\App\Http\Controllers\Adherent\ActualiteController::class, 'show'])
            ->name('actualites.show');
    });
});

/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';