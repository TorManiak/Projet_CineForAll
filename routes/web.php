<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/connexion', function () {
    return view('connexion');
});

Route::get('/créer_compte', function () {
    return view('creer_compte');
});

Route::get('/admin/G_film', function () {
    return view('admin.G_film');
});

Route::get('/admin/G_acteur', function () {
    return view('admin.G_acteur');
});

Route::get('/admin/G_genre', function () {
    return view('admin.G_genre');
});

Route::get('/admin/G_cine&salle', function () {
    return view('admin.G_cine&salle');
});

Route::get('/admin/G_prog', function () {
    return view('admin.G_prog');
});
