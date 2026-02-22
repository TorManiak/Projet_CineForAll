<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $genreId = $request->query('genre');

        // Liste des genres pour le select
        $genres = DB::table('genre')
            ->select('idGen', 'libGen')
            ->orderBy('libGen')
            ->get();

        // Requête films + genres (sans acteurs)
        $filmsQuery = DB::table('film')
            ->leftJoin('avoir', 'film.idFil', '=', 'avoir.idFil')
            ->leftJoin('genre', 'avoir.idGen', '=', 'genre.idGen')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',
                'film.afiFil',
                'film.malVoyEnt',
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT genre.libGen ORDER BY genre.libGen SEPARATOR ', '), '') AS genres")
            )
            ->groupBy('film.idFil', 'film.nomFil', 'film.datFil', 'film.afiFil', 'film.malVoyEnt')
            ->orderBy('film.nomFil', 'asc');

        // Recherche par nom de film
        if ($search !== '') {
            $filmsQuery->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        // Filtre par genre (optionnel)
        if (!empty($genreId) && ctype_digit((string) $genreId) && (int) $genreId > 0) {
            $filmsQuery->where('genre.idGen', '=', (int) $genreId);
        }

        $films = $filmsQuery->get();

        return view('catalogue', [
            'films' => $films,
            'genres' => $genres,
            'selectedGenre' => $genreId,
            'search' => $search,
        ]);
    }
}
