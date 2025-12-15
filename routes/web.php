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

