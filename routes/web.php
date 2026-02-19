<?php

use App\Http\Controllers\Admin\ActeurAdminController;
use App\Http\Controllers\Admin\CinemaAdminController;
use App\Http\Controllers\Admin\FilmAdminController;
use App\Http\Controllers\Admin\GenreAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('home');});

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/connexion', function () {
    return view('connexion');
});

Route::get('/créer_compte', function () {
    return view('creer_compte');
});

/* ADMIN */
Route::middleware('admin')->group(function () {

    // Pages
    Route::get('/admin/G_film', [FilmAdminController::class, 'index'])->name('admin.films.index');
    Route::get('/admin/G_acteur', [ActeurAdminController::class, 'index'])->name('admin.acteurs.index');
    Route::get('/admin/G_genre', [GenreAdminController::class, 'index'])->name('admin.genres.index');
    Route::get('/admin/G_cine_salle', [CinemaAdminController::class, 'index'])->name('admin.cinemas.index');

    // CRUD Films
    Route::post('/admin/G_film', [FilmAdminController::class, 'store'])->name('admin.films.store');
    Route::put('/admin/G_film/{idFil}', [FilmAdminController::class, 'update'])->name('admin.films.update');
    Route::delete('/admin/G_film/{idFil}', [FilmAdminController::class, 'destroy'])->name('admin.films.destroy');

    // CRUD Acteurs
    Route::post('/admin/G_acteur', [ActeurAdminController::class, 'store'])->name('admin.acteurs.store');
    Route::put('/admin/G_acteur/{idPer}', [ActeurAdminController::class, 'update'])->name('admin.acteurs.update');
    Route::delete('/admin/G_acteur/{idPer}', [ActeurAdminController::class, 'destroy'])->name('admin.acteurs.destroy');

    // CRUD Genres
    Route::post('/admin/G_genre', [GenreAdminController::class, 'store'])->name('admin.genres.store');
    Route::put('/admin/G_genre/{idGen}', [GenreAdminController::class, 'update'])->name('admin.genres.update');
    Route::delete('/admin/G_genre/{idGen}', [GenreAdminController::class, 'destroy'])->name('admin.genres.destroy');

    // CRUD Cinémas
    Route::post('/admin/G_cine_salle', [CinemaAdminController::class, 'store'])->name('admin.cinemas.store');
    Route::put('/admin/G_cine_salle/{idCin}', [CinemaAdminController::class, 'update'])->name('admin.cinemas.update');
    Route::delete('/admin/G_cine_salle/{idCin}', [CinemaAdminController::class, 'destroy'])->name('admin.cinemas.destroy');

    // Programmation (plus tard -> on laisse juste l'affichage)
    Route::get('/admin/G_prog', function () {
        return view('admin.G_prog');
    })->name('admin.prog');
});

/* UTILISATEUR */
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');


