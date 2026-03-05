<?php

use App\Http\Controllers\Admin\ActeurAdminController;
use App\Http\Controllers\Admin\CinemaAdminController;
use App\Http\Controllers\Admin\FilmAdminController;
use App\Http\Controllers\Admin\GenreAdminController;
use App\Http\Controllers\Admin\ProgrammationAdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\UserAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('home');});

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/créer_compte', [AuthController::class, 'showRegister'])->name('creer_compte');
Route::post('/créer_compte', [AuthController::class, 'register'])->name('register');

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

    // CRUD Programmation
    Route::get('/admin/G_prog', [ProgrammationAdminController::class, 'index'])->name('admin.prog');
    Route::post('/admin/G_prog', [ProgrammationAdminController::class, 'store'])->name('admin.prog.store');
    Route::put('/admin/G_prog/{idSea}', [ProgrammationAdminController::class, 'update'])->name('admin.prog.update');
    Route::delete('/admin/G_prog/{idSea}', [ProgrammationAdminController::class, 'destroy'])->name('admin.prog.destroy');

    // Salle
    Route::get('/admin/G_prog/salles', [ProgrammationAdminController::class, 'sallesByCinema'])
        ->name('admin.prog.salles');
});

/* UTILISATEUR */
Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');

Route::get('/films/{film}', [FilmController::class, 'show'])->name('films.show');

Route::get('/reservation', [ReservationController::class, 'index'])
    ->name('reservation.index')
    ->middleware(UserAuth::class);

Route::post('/films/{film}/note', [NoteController::class, 'store'])
    ->name('films.note')
    ->middleware(UserAuth::class);

// Création d'une réservation depuis la page film
Route::post('/reservations', [ReservationController::class, 'store'])
    ->name('reservations.store')
    ->middleware(UserAuth::class);

Route::delete('/reservation/{idRes}', [ReservationController::class, 'destroy'])
    ->name('reservation.destroy')
    ->middleware(UserAuth::class);

