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

        // Genres depuis film.typeFil (valeurs distinctes)
        $genres = DB::table('film')
            ->select('typeFil')
            ->whereNotNull('typeFil')
            ->where('typeFil', '<>', '')
            ->distinct()
            ->orderBy('typeFil')
            ->pluck('typeFil'); // => Collection de strings

        // Requête films (pas besoin de join genre/avoir utilises typeFil)
        $filmsQuery = DB::table('film')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',
                'film.afiFil',
                'film.desFil',
                'film.typeFil',
                'film.malVoyEnt',
                'film.banAnn'
            )
            ->orderBy('film.nomFil', 'asc');

        // Recherche par nom de film
        if ($search !== '') {
            $filmsQuery->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        // Filtre par genre (typeFil)
        if ($genreValue !== '') {
            $filmsQuery->where('film.typeFil', '=', $genreValue);
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
