<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FilmAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $filmsQuery = DB::table('film')
            ->select(
                'idFil',
                'nomFil',
                'datFil',
                'afiFil',
                'desFil',
                'typeFil',
                'malVoyEnt',
                'banAnn'
            )
            ->orderBy('nomFil', 'asc');

        if ($search !== '') {
            $filmsQuery->where('nomFil', 'LIKE', "%{$search}%");
        }

        $films = $filmsQuery->get();

        return view('admin.G_film', [
            'films' => $films,
            'search' => $search,
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'nomFil'    => ['required', 'string', 'max:255'],
            'datFil'    => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'genres'    => ['nullable', 'string', 'max:255'],
            'desFil'    => ['nullable', 'string'],
            'banAnn'    => ['nullable', 'string', 'max:255'],
            'malVoyEnt' => ['required', 'in:0,1'],
            'afiFil'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $nomFil = trim($request->nomFil);
        $datFil = trim($request->datFil);

        $typeFil = null;

        if ($request->filled('genres')) {
            $parts = array_filter(
                array_map('trim', explode(',', $request->genres))
            );

            $typeFil = $parts[0] ?? null;
        }

        $desFil = $request->filled('desFil') ? trim($request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim($request->banAnn) : null;

        $malVoyEnt = (int) $request->malVoyEnt;


        $afiFileName = null;

        if ($request->hasFile('afiFil')) {

            $file = $request->file('afiFil');

            $slug = Str::slug($nomFil);
            $ext  = $file->getClientOriginalExtension();

            $afiFileName = $slug . '-' . time() . '.' . $ext;

            $dest = public_path('img/films');

            if (!is_dir($dest)) {
                mkdir($dest, 0777, true);
            }

            $file->move($dest, $afiFileName);
        }
        try {

            DB::table('film')->insert([

                'nomFil'    => $nomFil,
                'datFil'    => $datFil,
                'afiFil'    => $afiFileName ?? '',
                'desFil'    => $desFil,
                'typeFil'   => $typeFil,
                'malVoyEnt' => $malVoyEnt,
                'banAnn'    => $banAnn,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Film ajouté avec succès');

        } catch (\Throwable $e) {

            if ($afiFileName) {
                $p = public_path('img/films/' . $afiFileName);
                if (file_exists($p)) unlink($p);
            }

            return redirect()
                ->back()
                ->withErrors([
                    'error' => $e->getMessage()
                ])
                ->withInput();
        }
    }
    public function update(Request $request, $idFil)
    {
        $request->validate([
            'nomFil'    => ['required', 'string', 'max:255'],
            'datFil'    => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'genres'    => ['nullable', 'string', 'max:255'],
            'desFil'    => ['nullable', 'string', 'max:255'], // IMPORTANT
            'banAnn'    => ['nullable', 'string', 'max:255'],
            'malVoyEnt' => ['required', 'in:0,1'],
            'afiFil'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $film = DB::table('film')->where('idFil', (int)$idFil)->first();
        if (!$film) {
            return redirect()->back()->withErrors(['error' => 'Film introuvable.']);
        }

        $nomFil = trim((string)$request->nomFil);
        $datFil = trim((string)$request->datFil);

        $typeFil = null;
        if ($request->filled('genres')) {
            $parts = array_filter(array_map('trim', explode(',', (string)$request->genres)));
            $typeFil = $parts[0] ?? null;
        }

        $desFil = $request->filled('desFil') ? trim((string)$request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim((string)$request->banAnn) : null;
        $malVoyEnt = (int) $request->malVoyEnt;

        $newAfiFileName = null;

        if ($request->hasFile('afiFil')) {
            $file = $request->file('afiFil');

            $slug = Str::slug($nomFil);
            $ext  = strtolower($file->getClientOriginalExtension());
            $newAfiFileName = $slug . '-' . time() . '.' . $ext;

            $dest = public_path('img/films');
            if (!is_dir($dest)) {
                @mkdir($dest, 0777, true);
            }

            $file->move($dest, $newAfiFileName);
        }

        DB::beginTransaction();
        try {
            $updateData = [
                'nomFil'    => $nomFil,
                'datFil'    => $datFil,
                'desFil'    => $desFil,
                'typeFil'   => $typeFil,
                'malVoyEnt' => $malVoyEnt,
                'banAnn'    => $banAnn,
            ];

            if ($newAfiFileName !== null) {
                $updateData['afiFil'] = $newAfiFileName;
            }

            DB::table('film')->where('idFil', (int)$idFil)->update($updateData);

            DB::commit();

            // supprimer l’ancienne affiche si on a upload une nouvelle
            if ($newAfiFileName !== null && !empty($film->afiFil)) {
                $oldPath = public_path('img/films/' . $film->afiFil);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            return redirect()->back()->with('success', 'Film modifié avec succès');
        } catch (\Throwable $e) {
            DB::rollBack();

            // si upload effectué mais update KO => supprimer le nouveau fichier
            if ($newAfiFileName !== null) {
                $p = public_path('img/films/' . $newAfiFileName);
                if (file_exists($p)) {
                    @unlink($p);
                }
            }

            return redirect()->back()->withErrors([
                'error' => $e->getMessage(),
            ])->withInput();
        }
    }

    public function destroy($idFil)
    {
        $film = DB::table('film')->where('idFil', (int) $idFil)->first();
        if (!$film) {
            return redirect()->back()->withErrors(['error' => 'Film introuvable.']);
        }

        DB::beginTransaction();

        try {
            // Si tu as des tables liées par idFil (FK), supprime d'abord les enfants.
            // On tente "avoir" et "jouer" si elles existent.
            try {
                DB::table('avoir')->where('idFil', (int) $idFil)->delete();
            } catch (\Throwable $e) {
                // ignore si table inexistante
            }

            try {
                DB::table('jouer')->where('idFil', (int) $idFil)->delete();
            } catch (\Throwable $e) {
                // ignore si table inexistante
            }

            // Supprime le film
            DB::table('film')->where('idFil', (int) $idFil)->delete();

            DB::commit();

            // supprimer l'affiche
            $old = (string) ($film->afiFil ?? '');
            if ($old !== '') {
                $p = public_path('img/films/' . $old);
                if (file_exists($p)) {
                    @unlink($p);
                }
            }

            return redirect()->back()->with('success', 'Film supprimé avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
