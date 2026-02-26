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

        // Acteurs + jointure nationalité (table nationalite)
        $acteursQuery = DB::table('personnalite as p')
            ->leftJoin('nationalite as n', 'n.idNat', '=', 'p.idNat')
            ->select(
                'p.idPer',
                'p.nomPer',
                'p.prePer',
                'p.datNaiPer',
                'p.desPer',
                'p.idNat',
                'n.nationalite as nationalite_label'
            )
            ->orderBy('p.idPer', 'desc');

        if ($search !== '') {
            $acteursQuery->where(function ($q) use ($search) {
                $q->where('p.nomPer', 'LIKE', "%{$search}%")
                    ->orWhere('p.prePer', 'LIKE', "%{$search}%")
                    ->orWhere('n.nationalite', 'LIKE', "%{$search}%");
            });
        }

        $acteurs = $acteursQuery->get();

        $films = DB::table('film')
            ->select('idFil', 'nomFil')
            ->orderBy('nomFil')
            ->get();

        $nationalites = DB::table('nationalite')
            ->select('idNat', 'nationalite')
            ->orderBy('nationalite')
            ->get();

        $roles = DB::table('type_de_role')
            ->select('idRolPer', 'libRol')
            ->orderBy('libRol')
            ->get();

        // map acteur -> [films...]
        $acteurFilms = DB::table('jouer')
            ->select('idPer', 'idFil')
            ->get()
            ->groupBy('idPer')
            ->map(fn($rows) => $rows->pluck('idFil')->map(fn($v) => (int) $v)->toArray())
            ->toArray();

        // map acteur -> role (idRolPer) (si plusieurs rôles, on prend le premier)
        $acteurRole = DB::table('jouer')
            ->select('idPer', 'idRolPer')
            ->whereNotNull('idRolPer')
            ->get()
            ->groupBy('idPer')
            ->map(function ($rows) {
                $v = $rows->pluck('idRolPer')->filter()->first();
                return $v ? (int) $v : null;
            })
            ->toArray();

        return view('admin.G_acteur', compact(
            'acteurs',
            'films',
            'nationalites',
            'roles',
            'acteurFilms',
            'acteurRole',
            'search'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomPer'     => ['required', 'string', 'max:100'],
            'prePer'     => ['required', 'string', 'max:100'],
            'datNaiPer'  => ['nullable', 'date'],
            'idNat'      => ['nullable', 'integer'],
            'desPer'     => ['nullable', 'string'],
            'idRolPer'   => ['nullable', 'integer'],
            'films'      => ['nullable', 'array'],
            'films.*'    => ['integer'],
        ]);

        $filmsIds = array_values(array_unique(array_map('intval', $data['films'] ?? [])));
        $idRolPer = isset($data['idRolPer']) && $data['idRolPer'] !== '' ? (int) $data['idRolPer'] : null;
        $idNat    = isset($data['idNat']) && $data['idNat'] !== '' ? (int) $data['idNat'] : null;

        DB::beginTransaction();
        try {
            // Sécuriser idNat (optionnel)
            if ($idNat !== null) {
                $existsNat = DB::table('nationalite')->where('idNat', $idNat)->exists();
                if (!$existsNat) $idNat = null;
            }

            // Sécuriser idRolPer (optionnel)
            if ($idRolPer !== null) {
                $existsRole = DB::table('type_de_role')->where('idRolPer', $idRolPer)->exists();
                if (!$existsRole) $idRolPer = null;
            }

            $idPer = DB::table('personnalite')->insertGetId([
                'nomPer'    => trim($data['nomPer']),
                'prePer'    => trim($data['prePer']),
                'datNaiPer' => $data['datNaiPer'] ?? null,
                'desPer'    => $data['desPer'] ?? null,
                'idNat'     => $idNat,
            ], 'idPer');

            if (!empty($filmsIds)) {
                // garder uniquement des films existants
                $existingFilms = DB::table('film')
                    ->whereIn('idFil', $filmsIds)
                    ->pluck('idFil')
                    ->map(fn($v) => (int) $v)
                    ->toArray();

                if (!empty($existingFilms)) {
                    $rows = [];
                    foreach ($existingFilms as $idFil) {
                        $rows[] = [
                            'idPer'    => $idPer,
                            'idFil'    => $idFil,
                            'idRolPer' => $idRolPer, // même rôle pour tous les films sélectionnés
                        ];
                    }
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
            'nomPer'     => ['required', 'string', 'max:100'],
            'prePer'     => ['required', 'string', 'max:100'],
            'datNaiPer'  => ['nullable', 'date'],
            'idNat'      => ['nullable', 'integer'],
            'desPer'     => ['nullable', 'string'],
            'idRolPer'   => ['nullable', 'integer'],
            'films'      => ['nullable', 'array'],
            'films.*'    => ['integer'],
        ]);

        $filmsIds = array_values(array_unique(array_map('intval', $data['films'] ?? [])));
        $idRolPer = isset($data['idRolPer']) && $data['idRolPer'] !== '' ? (int) $data['idRolPer'] : null;
        $idNat    = isset($data['idNat']) && $data['idNat'] !== '' ? (int) $data['idNat'] : null;

        DB::beginTransaction();
        try {
            // acteur existe ?
            $exists = DB::table('personnalite')->where('idPer', $idPer)->exists();
            if (!$exists) {
                DB::rollBack();
                return redirect()->back()->withErrors(['error' => 'Acteur introuvable.']);
            }

            // Sécuriser idNat (optionnel)
            if ($idNat !== null) {
                $existsNat = DB::table('nationalite')->where('idNat', $idNat)->exists();
                if (!$existsNat) $idNat = null;
            }

            // Sécuriser idRolPer (optionnel)
            if ($idRolPer !== null) {
                $existsRole = DB::table('type_de_role')->where('idRolPer', $idRolPer)->exists();
                if (!$existsRole) $idRolPer = null;
            }

            DB::table('personnalite')->where('idPer', $idPer)->update([
                'nomPer'    => trim($data['nomPer']),
                'prePer'    => trim($data['prePer']),
                'datNaiPer' => $data['datNaiPer'] ?? null,
                'desPer'    => $data['desPer'] ?? null,
                'idNat'     => $idNat,
            ]);

            // Sync jouer (films + rôle)
            DB::table('jouer')->where('idPer', $idPer)->delete();

            if (!empty($filmsIds)) {
                $existingFilms = DB::table('film')
                    ->whereIn('idFil', $filmsIds)
                    ->pluck('idFil')
                    ->map(fn($v) => (int) $v)
                    ->toArray();

                if (!empty($existingFilms)) {
                    $rows = [];
                    foreach ($existingFilms as $idFil) {
                        $rows[] = [
                            'idPer'    => $idPer,
                            'idFil'    => $idFil,
                            'idRolPer' => $idRolPer,
                        ];
                    }
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
