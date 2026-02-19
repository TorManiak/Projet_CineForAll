<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = DB::table('film')
            ->leftJoin('avoir', 'film.idFil', '=', 'avoir.idFil')
            ->leftJoin('genre', 'avoir.idGen', '=', 'genre.idGen')
            ->leftJoin('jouer', 'film.idFil', '=', 'jouer.idFil')
            ->leftJoin('personnalite', 'jouer.idPer', '=', 'personnalite.idPer')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',     // dans ta BDD ça correspond à la durée
                'film.afiFil',     // image fichier (ex: inception.jpg)
                'film.malVoyEnt',
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT genre.libGen ORDER BY genre.libGen SEPARATOR ', '), '') AS genres"),
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT CONCAT(personnalite.prePer, ' ', personnalite.nomPer) ORDER BY personnalite.nomPer SEPARATOR ', '), '') AS acteurs")
            )
            ->groupBy('film.idFil', 'film.nomFil', 'film.datFil', 'film.afiFil', 'film.malVoyEnt')
            ->orderBy('film.nomFil', 'asc');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('film.nomFil', 'LIKE', "%{$search}%")
                    ->orWhere('personnalite.nomPer', 'LIKE', "%{$search}%")
                    ->orWhere('personnalite.prePer', 'LIKE', "%{$search}%");
            });
        }

        $films = $query->get();

        return view('catalogue', compact('films', 'search'));
    }
}
