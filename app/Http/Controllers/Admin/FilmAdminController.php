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

        // Genres pour dropdown
        $genres = DB::table('genre')
            ->select('idGen', 'libGen')
            ->orderBy('libGen', 'asc')
            ->get();

        // Films + libellé genre
        $filmsQuery = DB::table('film')
            ->leftJoin('genre', 'film.idGen', '=', 'genre.idGen')
            ->select(
                'film.idFil',
                'film.nomFil',
                'film.datFil',
                'film.afiFil',
                'film.desFil',
                'film.idGen',
                'genre.libGen as genreLib',
                'film.malVoyEnt',
                'film.banAnn'
            )
            ->orderBy('film.nomFil', 'asc');

        if ($search !== '') {
            $filmsQuery->where('film.nomFil', 'LIKE', "%{$search}%");
        }

        $films = $filmsQuery->get();

        return view('admin.G_film', [
            'films' => $films,
            'genres' => $genres,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomFil'    => ['required', 'string', 'max:255'],
            'datFil'    => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'idGen'     => ['required', 'integer'],
            'desFil'    => ['nullable', 'string'],
            'banAnn'    => ['nullable', 'string', 'max:255'],
            'malVoyEnt' => ['required', 'in:0,1'],
            'afiFil'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $nomFil = trim((string)$request->nomFil);
        $datFil = trim((string)$request->datFil);
        $idGen  = (int)$request->idGen;

        // Vérifie que le genre existe
        $genreExists = DB::table('genre')->where('idGen', $idGen)->exists();
        if (!$genreExists) {
            return redirect()->back()->withErrors(['idGen' => 'Genre invalide.'])->withInput();
        }

        $desFil = $request->filled('desFil') ? trim((string)$request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim((string)$request->banAnn) : null;
        $malVoyEnt = (int)$request->malVoyEnt;

        $afiFileName = null;

        if ($request->hasFile('afiFil')) {
            $file = $request->file('afiFil');

            $slug = Str::slug($nomFil);
            $ext  = strtolower($file->getClientOriginalExtension());

            // IMPORTANT : garder un nom unique
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
                'idGen'     => $idGen,
                'malVoyEnt' => $malVoyEnt,
                'banAnn'    => $banAnn,
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
            'nomFil'    => ['required', 'string', 'max:255'],
            'datFil'    => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'idGen'     => ['required', 'integer'],
            'desFil'    => ['nullable', 'string'],
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
        $idGen  = (int)$request->idGen;

        $genreExists = DB::table('genre')->where('idGen', $idGen)->exists();
        if (!$genreExists) {
            return redirect()->back()->withErrors(['idGen' => 'Genre invalide.'])->withInput();
        }

        $desFil = $request->filled('desFil') ? trim((string)$request->desFil) : null;
        $banAnn = $request->filled('banAnn') ? trim((string)$request->banAnn) : null;
        $malVoyEnt = (int)$request->malVoyEnt;

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
                'idGen'     => $idGen,
                'malVoyEnt' => $malVoyEnt,
                'banAnn'    => $banAnn,
            ];

            if ($newAfiFileName !== null) {
                $updateData['afiFil'] = $newAfiFileName;
            }

            DB::table('film')->where('idFil', (int)$idFil)->update($updateData);

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
        $film = DB::table('film')->where('idFil', (int)$idFil)->first();
        if (!$film) {
            return redirect()->back()->withErrors(['error' => 'Film introuvable.']);
        }

        DB::beginTransaction();

        try {
            try {
                DB::table('avoir')->where('idFil', (int)$idFil)->delete();
            } catch (\Throwable $e) {
            }

            try {
                DB::table('jouer')->where('idFil', (int)$idFil)->delete();
            } catch (\Throwable $e) {
            }

            DB::table('film')->where('idFil', (int)$idFil)->delete();

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
