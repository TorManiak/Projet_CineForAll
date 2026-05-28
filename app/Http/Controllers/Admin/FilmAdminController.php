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

        $classifications = DB::table('classification')
            ->select('idClass', 'classification')
            ->orderBy('idClass')
            ->get();
        $genres = DB::table('genre')
            ->select('idGen', 'libGen')
            ->orderBy('libGen', 'asc')
            ->get();

        $filmsQuery = DB::table('film')
            ->leftJoin('genre', 'film.idGen', '=', 'genre.idGen')
            ->leftJoin('classification', 'film.classification', '=', 'classification.idClass')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',
                'film.afiFil',
                'film.desFil',
                'film.idGen',
                'film.malVoyEnt',
                'film.banAnn',
                'film.annSor',
                'film.classification',
                'genre.libGen as genreLib',
                'classification.classification as classificationLib'
            )
            ->orderBy('film.nomFil', 'asc');

        if ($search !== '') {
            $filmsQuery->where(function ($query) use ($search) {
                $query->where('film.nomFil', 'LIKE', "%{$search}%")
                    ->orWhere('genre.libGen', 'LIKE', "%{$search}%");
            });
        }

        $films = $filmsQuery->get();

        return view('admin.G_film', [
            'films' => $films,
            'genres' => $genres,
            'classifications' => $classifications,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomFil'         => ['required', 'string', 'max:255'],
            'datFil'         => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'idGen'          => ['required', 'integer'],
            'desFil'         => ['nullable', 'string'],
            'banAnn'         => ['nullable', 'string', 'max:255'],
            'malVoyEnt'      => ['required', 'in:0,1'],
            'annSor'         => ['required', 'integer', 'digits:4'],
            'classification' => ['required', 'integer'],
            'afiFil'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $nomFil = trim((string) $request->nomFil);
        $datFil = trim((string) $request->datFil);
        $idGen = (int) $request->idGen;
        $desFil = $request->filled('desFil') ? trim((string) $request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim((string) $request->banAnn) : null;
        $malVoyEnt = (int) $request->malVoyEnt;
        $annSor = (int) $request->annSor;
        $classification = (int) $request->classification;

        $genreExists = DB::table('genre')->where('idGen', $idGen)->exists();
        if (!$genreExists) {
            return redirect()->back()->withErrors(['idGen' => 'Genre invalide.'])->withInput();
        }

        $afiFileName = null;

        if ($request->hasFile('afiFil')) {
            $file = $request->file('afiFil');

            $slug = Str::slug($nomFil);
            $ext = strtolower($file->getClientOriginalExtension());

            $afiFileName = $slug . '-' . time() . '.' . $ext;

            $dest = public_path('img/films');
            if (!is_dir($dest)) {
                mkdir($dest, 0777, true);
            }

            $file->move($dest, $afiFileName);
        }

        try {
            DB::table('film')->insert([
                'nomFil'         => $nomFil,
                'datFil'         => $datFil,
                'afiFil'         => $afiFileName ?? '',
                'desFil'         => $desFil,
                'idGen'          => $idGen,
                'malVoyEnt'      => $malVoyEnt,
                'banAnn'         => $banAnn,
                'annSor'         => $annSor,
                'classification' => $classification,
            ]);

            return redirect()->back()->with('success', 'Film ajouté avec succès');
        } catch (\Throwable $e) {
            if ($afiFileName) {
                $p = public_path('img/films/' . $afiFileName);
                if (file_exists($p)) {
                    unlink($p);
                }
            }

            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, $idFil)
    {
        $request->validate([
            'nomFil'         => ['required', 'string', 'max:255'],
            'datFil'         => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'idGen'          => ['required', 'integer'],
            'desFil'         => ['nullable', 'string'],
            'banAnn'         => ['nullable', 'string', 'max:255'],
            'malVoyEnt'      => ['required', 'in:0,1'],
            'annSor'         => ['required', 'integer', 'digits:4'],
            'classification' => ['required', 'integer'],
            'afiFil'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $film = DB::table('film')->where('idFil', (int) $idFil)->first();
        if (!$film) {
            return redirect()->back()->withErrors(['error' => 'Film introuvable.']);
        }

        $nomFil = trim((string) $request->nomFil);
        $datFil = trim((string) $request->datFil);
        $idGen = (int) $request->idGen;
        $desFil = $request->filled('desFil') ? trim((string) $request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim((string) $request->banAnn) : null;
        $malVoyEnt = (int) $request->malVoyEnt;
        $annSor = (int) $request->annSor;
        $classification = (int) $request->classification;

        $genreExists = DB::table('genre')->where('idGen', $idGen)->exists();
        if (!$genreExists) {
            return redirect()->back()->withErrors(['idGen' => 'Genre invalide.'])->withInput();
        }

        $newAfiFileName = null;

        if ($request->hasFile('afiFil')) {
            $file = $request->file('afiFil');

            $slug = Str::slug($nomFil);
            $ext = strtolower($file->getClientOriginalExtension());
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
                'nomFil'         => $nomFil,
                'datFil'         => $datFil,
                'desFil'         => $desFil,
                'idGen'          => $idGen,
                'malVoyEnt'      => $malVoyEnt,
                'banAnn'         => $banAnn,
                'annSor'         => $annSor,
                'classification' => $classification,
            ];

            if ($newAfiFileName !== null) {
                $updateData['afiFil'] = $newAfiFileName;
            }

            DB::table('film')->where('idFil', (int) $idFil)->update($updateData);

            DB::commit();

            if ($newAfiFileName !== null && !empty($film->afiFil)) {
                $oldPath = public_path('img/films/' . $film->afiFil);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            return redirect()->back()->with('success', 'Film modifié avec succès');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($newAfiFileName !== null) {
                $p = public_path('img/films/' . $newAfiFileName);
                if (file_exists($p)) {
                    @unlink($p);
                }
            }

            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
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
            try {
                DB::table('avoir')->where('idFil', (int) $idFil)->delete();
            } catch (\Throwable $e) {
            }

            try {
                DB::table('jouer')->where('idFil', (int) $idFil)->delete();
            } catch (\Throwable $e) {
            }

            DB::table('film')->where('idFil', (int) $idFil)->delete();

            DB::commit();

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
