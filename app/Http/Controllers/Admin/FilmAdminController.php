<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilmAdminController extends Controller
{
    public function index()
    {
        $films = DB::table('film')
            ->orderBy('idFil', 'desc')
            ->get();

        $genres = DB::table('genre')->orderBy('libGen')->get();

        // Map film -> genres (via pivot "avoir")
        $filmGenres = DB::table('avoir')
            ->select('idFil', 'idGen')
            ->get()
            ->groupBy('idFil')
            ->map(fn($rows) => $rows->pluck('idGen')->toArray())
            ->toArray();

        return view('admin.G_film', compact('films', 'genres', 'filmGenres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomFil' => ['required', 'string', 'max:255'],
            'datFil' => ['nullable', 'string'], // ex "02:28:00"
            'afiFil' => ['nullable', 'string', 'max:255'], // ex "inception.jpg"
            'desFil' => ['nullable', 'string'],
            'typeFil' => ['nullable', 'string', 'max:255'], // si tu gardes ce champ
            'banAnn' => ['nullable', 'string', 'max:255'], // url youtube
            'malVoyEnt' => ['nullable', 'in:0,1'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer'],
        ]);

        $idFil = DB::table('film')->insertGetId([
            'nomFil' => $data['nomFil'],
            'datFil' => $data['datFil'] ?? null,
            'afiFil' => $data['afiFil'] ?? null,
            'desFil' => $data['desFil'] ?? null,
            'typeFil' => $data['typeFil'] ?? null,
            'banAnn' => $data['banAnn'] ?? null,
            'malVoyEnt' => isset($data['malVoyEnt']) ? (int)$data['malVoyEnt'] : 0,
        ], 'idFil');

        // Pivot avoir (film-genre)
        if (!empty($data['genres'])) {
            $rows = [];
            foreach ($data['genres'] as $idGen) {
                $rows[] = ['idFil' => $idFil, 'idGen' => (int)$idGen];
            }
            DB::table('avoir')->insert($rows);
        }

        return redirect()->route('admin.films.index')->with('success', 'Film ajouté.');
    }

    public function update(Request $request, int $idFil)
    {
        $data = $request->validate([
            'nomFil' => ['required', 'string', 'max:255'],
            'datFil' => ['nullable', 'string'],
            'afiFil' => ['nullable', 'string', 'max:255'],
            'desFil' => ['nullable', 'string'],
            'typeFil' => ['nullable', 'string', 'max:255'],
            'banAnn' => ['nullable', 'string', 'max:255'],
            'malVoyEnt' => ['nullable', 'in:0,1'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['integer'],
        ]);

        DB::table('film')->where('idFil', $idFil)->update([
            'nomFil' => $data['nomFil'],
            'datFil' => $data['datFil'] ?? null,
            'afiFil' => $data['afiFil'] ?? null,
            'desFil' => $data['desFil'] ?? null,
            'typeFil' => $data['typeFil'] ?? null,
            'banAnn' => $data['banAnn'] ?? null,
            'malVoyEnt' => isset($data['malVoyEnt']) ? (int)$data['malVoyEnt'] : 0,
        ]);

        // Sync pivot avoir
        DB::table('avoir')->where('idFil', $idFil)->delete();
        if (!empty($data['genres'])) {
            $rows = [];
            foreach ($data['genres'] as $idGen) {
                $rows[] = ['idFil' => $idFil, 'idGen' => (int)$idGen];
            }
            DB::table('avoir')->insert($rows);
        }

        return redirect()->route('admin.films.index')->with('success', 'Film modifié.');
    }

    public function destroy(int $idFil)
    {
        DB::table('avoir')->where('idFil', $idFil)->delete();
        DB::table('jouer')->where('idFil', $idFil)->delete(); // si pivot acteur-film
        DB::table('film')->where('idFil', $idFil)->delete();

        return redirect()->route('admin.films.index')->with('success', 'Film supprimé.');
    }
}
