<?php

namespace App\Http\Controllers;

use App\Models\Film;

class FilmController extends Controller
{
    public function show(Film $film)
    {
        // Table "jouer" pas remplie => pas de réalisateur/casting ici pour l’instant
        return view('show', compact('film'));
    }
}
