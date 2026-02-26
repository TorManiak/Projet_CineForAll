<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActeurAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $acteursQuery = DB::table('personnalite')->orderBy('idPer', 'desc');

        if ($search !== '') {
            $acteursQuery->where(function ($q) use ($search) {
                $q->where('nomPer', 'LIKE', "%{$search}%")
                    ->orWhere('prePer', 'LIKE', "%{$search}%")
                    ->orWhere('natPer', 'LIKE', "%{$search}%");
            });
        }

        $acteurs = $acteursQuery->get();
        $films   = DB::table('film')->orderBy('nomFil')->get();

        // map acteur -> [idFil, idFil...]
        $acteurFilms = DB::table('jouer')
            ->select('idPer', 'idFil')
            ->get()
            ->groupBy('idPer')
            ->map(fn($rows) => $rows->pluck('idFil')->map(fn($v) => (int)$v)->toArray())
            ->toArray();

        return view('admin.G_acteur', compact('acteurs', 'films', 'acteurFilms', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomPer'    => ['required', 'string', 'max:100'],
            'prePer'    => ['required', 'string', 'max:100'],
            'datNaiPer' => ['nullable', 'date'],
            'natPer'    => ['nullable', 'string', 'max:100'],
            'desPer'    => ['nullable', 'string', 'max:100'],
            'bibPer'    => ['nullable', 'string', 'max:100'],
            'films'     => ['nullable', 'array'],
            'films.*'   => ['integer'],
        ]);

        $filmsIds = array_values(array_unique(array_map('intval', $data['films'] ?? [])));

        DB::beginTransaction();
        try {
            $idPer = DB::table('personnalite')->insertGetId([
                'nomPer'    => $data['nomPer'],
                'prePer'    => $data['prePer'],
                'datNaiPer' => $data['datNaiPer'] ?? null,
                'natPer'    => $data['natPer'] ?? null,
                'desPer'    => $data['desPer'] ?? null,
                'bibPer'    => $data['bibPer'],
            ], 'idPer');

            if (!empty($filmsIds)) {
                $existing = DB::table('film')
                    ->whereIn('idFil', $filmsIds)
                    ->pluck('idFil')
                    ->map(fn($v) => (int)$v)
                    ->toArray();

                if (!empty($existing)) {
                    $rows = array_map(fn($idFil) => ['idPer' => $idPer, 'idFil' => $idFil], $existing);
                    DB::table('jouer')->insert($rows);
                }
            }

            DB::commit();
            return redirect()->route('admin.acteurs.index')->with('success', 'Acteur ajouté.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, int $idPer)
    {
        $data = $request->validate([
            'nomPer'    => ['required', 'string', 'max:100'],
            'prePer'    => ['required', 'string', 'max:100'],
            'datNaiPer' => ['nullable', 'date'],
            'natPer'    => ['nullable', 'string', 'max:100'],
            'bibPer'    => ['nullable', 'string'],
            'desPer'    => ['nullable', 'string'],
            'films'     => ['nullable', 'array'],
            'films.*'   => ['integer'],
        ]);

        $filmsIds = array_values(array_unique(array_map('intval', $data['films'] ?? [])));

        DB::beginTransaction();
        try {
            DB::table('personnalite')->where('idPer', $idPer)->update([
                'nomPer'    => $data['nomPer'],
                'prePer'    => $data['prePer'],
                'datNaiPer' => $data['datNaiPer'] ?? null,
                'natPer'    => $data['natPer'] ?? null,
                'bibPer'    => $data['bibPer'] ,
                'desPer'    => $data['desPer'] ?? null,
            ]);

            // sync jouer
            DB::table('jouer')->where('idPer', $idPer)->delete();

            if (!empty($filmsIds)) {
                $existing = DB::table('film')
                    ->whereIn('idFil', $filmsIds)
                    ->pluck('idFil')
                    ->map(fn($v) => (int)$v)
                    ->toArray();

                if (!empty($existing)) {
                    $rows = array_map(fn($idFil) => ['idPer' => $idPer, 'idFil' => $idFil], $existing);
                    DB::table('jouer')->insert($rows);
                }
            }

            DB::commit();
            return redirect()->route('admin.acteurs.index')->with('success', 'Acteur modifié.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(int $idPer)
    {
        DB::beginTransaction();
        try {
            DB::table('jouer')->where('idPer', $idPer)->delete();
            DB::table('personnalite')->where('idPer', $idPer)->delete();
            DB::commit();
            return redirect()->route('admin.acteurs.index')->with('success', 'Acteur supprimé.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
