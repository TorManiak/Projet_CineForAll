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
        $anneeValue = trim((string) $request->query('annee', '')); // IMPORTANT: annee (comme dans le select)
        $popValue   = trim((string) $request->query('pop', ''));   // asc|desc

        // Genres dropdown
        $genres = DB::table('genre')
            ->select('idGen', 'libGen')
            ->orderBy('libGen', 'asc')
            ->get();

        /**
         * Requête films + recherche acteur
         * - si $search correspond à un acteur => on récupère les films via jouer/personnalite
         * - sinon recherche classique sur titre
         */
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
                'film.annSor'
            )
            ->distinct();

        // Recherche "film OU acteur"
        if ($search !== '') {
            $filmsQuery->where(function ($q) use ($search) {
                // Recherche titre film
                $q->where('film.nomFil', 'LIKE', "%{$search}%")
                    // OU recherche acteur via EXISTS sur jouer/personnalite
                    ->orWhereExists(function ($sub) use ($search) {
                        $sub->select(DB::raw(1))
                            ->from('jouer')
                            ->join('personnalite', 'personnalite.idPer', '=', 'jouer.IdPer')
                            ->whereColumn('jouer.IdFil', 'film.idFil')
                            ->where(function ($qq) use ($search) {
                                $qq->where('personnalite.nomPer', 'LIKE', "%{$search}%")
                                    ->orWhere('personnalite.prePer', 'LIKE', "%{$search}%")
                                    ->orWhere(DB::raw("CONCAT(personnalite.prePer,' ',personnalite.nomPer)"), 'LIKE', "%{$search}%")
                                    ->orWhere(DB::raw("CONCAT(personnalite.nomPer,' ',personnalite.prePer)"), 'LIKE', "%{$search}%");
                            });
                    });
            });
        }

        // Filtre genre
        if ($genreValue !== '') {
            $filmsQuery->where('film.idGen', '=', $genreValue);
        }

        // Filtre année (annSor)
        if ($anneeValue !== '') {
            $filmsQuery->where('film.annSor', '=', $anneeValue);
        }

        // Tri popularité (si tu n'as pas de champ popularité, on ne fait rien)
        // Si tu as un champ genre "popularity" ou "note" ou "nbVue", remplace ici.
        if ($popValue === 'asc') {
            // Exemple si tu as "film.popularite"
            // $filmsQuery->orderBy('film.popularite', 'asc');
            $filmsQuery->orderBy('film.nomFil', 'asc');
        } elseif ($popValue === 'desc') {
            // $filmsQuery->orderBy('film.popularite', 'desc');
            $filmsQuery->orderBy('film.nomFil', 'asc');
        } else {
            $filmsQuery->orderBy('film.nomFil', 'asc');
        }

        $films = $filmsQuery->get();

        return view('catalogue', [
            'films' => $films,
            'genres' => $genres,
            'selectedGenre' => $genreValue,
            'selectedAnnee' => $anneeValue,
            'selectedPop' => $popValue,
            'search' => $search,
        ]);
    }
}
