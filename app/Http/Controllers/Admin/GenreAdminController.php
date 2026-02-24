<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreAdminController extends Controller
{
    public function index()
    {
        $genres = DB::table('genre')->orderBy('libGen', 'asc')->get();

        return view('admin.G_genre', [
            'genres' => $genres,
        ]);
    }

    public function store(Request $request)
    {
        // 1) Validation : une seule valeur, pas de chiffres, lettres + espaces + tiret + apostrophe
        $request->validate([
            'libGen' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\' -]+$/',
            ],
        ], [
            'libGen.required' => 'Le genre est obligatoire.',
            'libGen.regex' => 'Le genre ne doit contenir que des lettres (pas de chiffres ni caractères spéciaux).',
            'libGen.max' => 'Le genre est trop long (50 caractères max).',
        ]);

        $libGen = trim((string)$request->input('libGen'));

        // 2) Interdire plusieurs genres séparés par virgule (un seul genre)
        if (str_contains($libGen, ',') || str_contains($libGen, ';') || str_contains($libGen, '/')) {
            return redirect()->back()
                ->withErrors(['libGen' => 'Un seul genre à la fois (pas de virgule / point-virgule / slash).'])
                ->withInput();
        }

        // 3) Normalisation : "action" => "Action"
        $libGen = mb_strtolower($libGen, 'UTF-8');
        $libGen = mb_strtoupper(mb_substr($libGen, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($libGen, 1, null, 'UTF-8');

        // 4) Empêcher les doublons (insensible à la casse)
        $exists = DB::table('genre')
            ->whereRaw('LOWER(libGen) = ?', [mb_strtolower($libGen, 'UTF-8')])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['libGen' => 'Ce genre existe déjà.'])
                ->withInput();
        }


        DB::table('genre')->insert([
            'libGen' => $libGen,
        ]);

        return redirect()->back()->with('success', 'Genre ajouté.');
    }
}
