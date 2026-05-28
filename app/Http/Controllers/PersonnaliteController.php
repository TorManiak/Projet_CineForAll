<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonnaliteController extends Controller
{
    // Liste + recherche
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = DB::table('personnalite')
            ->leftJoin('nationalite', 'nationalite.idNat', '=', 'personnalite.idNat')
            ->select(
                'personnalite.idPer',
                'personnalite.nomPer',
                'personnalite.prePer',
                'personnalite.datNaiPer',
                'personnalite.desPer',
                'nationalite.nationalite'
            );

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('personnalite.nomPer', 'LIKE', "%{$search}%")
                    ->orWhere('personnalite.prePer', 'LIKE', "%{$search}%")
                    ->orWhere(
                        DB::raw("CONCAT(personnalite.prePer, ' ', personnalite.nomPer)"),
                        'LIKE', "%{$search}%"
                    );
            });
        }

        $personnalites = $query->orderBy('personnalite.nomPer')->get();

        return view('personnalites', [
            'personnalites' => $personnalites,
            'search'        => $search,
        ]);
    }

    // Fiche détail
    public function show($idPer)
    {
        $personnalite = DB::table('personnalite')
            ->leftJoin('nationalite', 'nationalite.idNat', '=', 'personnalite.idNat')
            ->where('personnalite.idPer', $idPer)
            ->select(
                'personnalite.*',
                'nationalite.nationalite'
            )
            ->first();

        if (!$personnalite) {
            abort(404);
        }

        // Films dans lesquels la personnalité a joué
        $films = DB::table('jouer')
            ->join('film', 'film.idFil', '=', 'jouer.idFil')
            ->join('type_de_role', 'type_de_role.idRolPer', '=', 'jouer.idRolPer')
            ->where('jouer.idPer', $idPer)
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.annSor',
                'film.afiFil',
                'film.desFil',
                'type_de_role.libRol'
            )
            ->orderBy('film.annSor', 'desc')
            ->get();

        return view('personnalites_show', [
            'personnalite' => $personnalite,
            'films'        => $films,
        ]);
    }
}
