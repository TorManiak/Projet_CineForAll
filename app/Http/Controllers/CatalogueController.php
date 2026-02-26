<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $search     = trim((string) $request->query('search', ''));
        $genreValue = trim((string) $request->query('genre', ''));
        $anneValue = trim((string) $request->query('year', ''));

        // Genres depuis film.typeFil (valeurs distinctes)
        $genres = DB::table('genre')
            ->select('idGen', 'libGen')
            ->orderBy('libGen', 'asc')
            ->get();

        // Requête films (pas besoin de join genre/avoir utilises typeFil)
        $filmsQuery = DB::table('film')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',
                'film.afiFil',
                'film.desFil',
                'film.idGen',
                'film.malVoyEnt',
                'film.banAnn',
                'film.annSor',
            )
            ->orderBy('film.nomFil', 'asc');

        // Recherche par nom de film
        if ($search !== '') {
            $filmsQuery->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        // Filtre par genre
        if ($genreValue !== '') {
            $filmsQuery->where('film.idGen', '=', $genreValue);
        }

        //Filtre par annee
        if ($anneValue !== '') {
            $filmsQuery->where('film.annSor', '=', $anneValue);
        }

        $films = $filmsQuery->get();

        return view('catalogue', [
            'films' => $films,
            'genres' => $genres,
            'selectedGenre' => $genreValue,
            'search' => $search,
        ]);
    }
}
