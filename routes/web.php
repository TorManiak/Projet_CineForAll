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
