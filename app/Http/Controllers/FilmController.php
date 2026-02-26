<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Support\Facades\DB;

class FilmController extends Controller
{
    public function show(Film $film)
    {
        $filmRow = DB::table('film')
            ->where('idFil', (int)$film->idFil)
            ->first();

        if (!$filmRow) {
            abort(404);
        }

        //Récupération des personnes liées au film
        $people = DB::table('jouer')
            ->join('personnalite', 'personnalite.idPer', '=', 'jouer.idPer')
            ->join('type_de_role', 'type_de_role.idRolPer', '=', 'jouer.idRolPer')
            ->where('jouer.idFil', (int)$filmRow->idFil)
            ->select(
                'personnalite.nomPer',
                'personnalite.prePer',
                'type_de_role.libRol'
            )
            ->get();

        // Réalisateurs
        $realisateurs = $people
            ->filter(fn($p) => in_array(strtolower($p->libRol), ['realisateur', 'co-realisateur']))
            ->map(fn($p) => $p->prePer . ' ' . $p->nomPer)
            ->values()
            ->all();

        // Acteurs
        $casting = $people
            ->filter(fn($p) => strtolower($p->libRol) === 'acteur')
            ->map(fn($p) => $p->prePer . ' ' . $p->nomPer)
            ->values()
            ->all();

        // Langues (via film_langue)
        $langues = DB::table('film_langue')
            ->join('langue', 'langue.idLan', '=', 'film_langue.idLan')
            ->where('film_langue.idFil', (int)$filmRow->idFil)
            ->pluck('langue.langue')
            ->toArray();

        return view('show', [
            'film' => $filmRow,
            'realisateurs' => $realisateurs,
            'casting' => $casting,
            'langues' => $langues,
        ]);
    }
}
