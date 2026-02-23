<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActeurAdminController extends Controller
{
    public function index()
    {
        $acteurs = DB::table('personnalite')->orderBy('idPer', 'desc')->get();
        $films = DB::table('film')->orderBy('nomFil')->get();

        // map acteur -> films via jouer
        $acteurFilms = DB::table('jouer')
            ->select('idPer', 'idFil')
            ->get()
            ->groupBy('idPer')
            ->map(fn($rows) => $rows->pluck('idFil')->toArray())
            ->toArray();

        return view('admin.G_acteur', compact('acteurs', 'films', 'acteurFilms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomPer' => ['required', 'string', 'max:100'],
            'prePer' => ['required', 'string', 'max:100'],
            'datNaiPer' => ['nullable', 'date'],
            'natPer' => ['nullable', 'string', 'max:100'],
            'bibPer' => ['nullable', 'string'],
            'desPer' => ['nullable', 'string'], // description
            'films' => ['nullable', 'array'],
            'films.*' => ['integer'],
        ]);

        $idPer = DB::table('personnalite')->insertGetId([
            'nomPer' => $data['nomPer'],
            'prePer' => $data['prePer'],
            'datNaiPer' => $data['datNaiPer'] ?? null,
            'natPer' => $data['natPer'] ?? null,
            'bibPer' => $data['bibPer'] ?? null,
            'desPer' => $data['desPer'] ?? null,
        ], 'idPer');

        if (!empty($data['films'])) {
            $rows = [];
            foreach ($data['films'] as $idFil) {
                $rows[] = ['idPer' => $idPer, 'idFil' => (int)$idFil];
            }
            DB::table('jouer')->insert($rows);
        }

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur ajouté.');
    }

    public function update(Request $request, int $idPer)
    {
        $data = $request->validate([
            'nomPer' => ['required', 'string', 'max:100'],
            'prePer' => ['required', 'string', 'max:100'],
            'datNaiPer' => ['nullable', 'date'],
            'natPer' => ['nullable', 'string', 'max:100'],
            'bibPer' => ['nullable', 'string'],
            'desPer' => ['nullable', 'string'],
            'films' => ['nullable', 'array'],
            'films.*' => ['integer'],
        ]);

        DB::table('personnalite')->where('idPer', $idPer)->update([
            'nomPer' => $data['nomPer'],
            'prePer' => $data['prePer'],
            'datNaiPer' => $data['datNaiPer'] ?? null,
            'natPer' => $data['natPer'] ?? null,
            'bibPer' => $data['bibPer'] ?? null,
            'desPer' => $data['desPer'] ?? null,
        ]);

        // sync pivot jouer
        DB::table('jouer')->where('idPer', $idPer)->delete();
        if (!empty($data['films'])) {
            $rows = [];
            foreach ($data['films'] as $idFil) {
                $rows[] = ['idPer' => $idPer, 'idFil' => (int)$idFil];
            }
            DB::table('jouer')->insert($rows);
        }

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur modifié.');
    }

    public function destroy(int $idPer)
    {
        DB::table('jouer')->where('idPer', $idPer)->delete();
        DB::table('personnalite')->where('idPer', $idPer)->delete();

        return redirect()->route('admin.acteurs.index')->with('success', 'Acteur supprimé.');
    }
}
