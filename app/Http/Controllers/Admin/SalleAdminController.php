<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalleAdminController extends Controller
{
    public function index($idCin)
    {
        $cinema = DB::table('cinema')->where('idCin', (int) $idCin)->first();

        if (!$cinema) {
            return redirect()->back()->withErrors(['error' => 'Cinéma introuvable.']);
        }

        $salles = DB::table('salle')
            ->where('idCin', (int) $idCin)
            ->orderBy('nomSal', 'asc')
            ->get();

        $typesSalle = ['Normal', '3D', '4D', 'IMAX', 'XL', 'Premium', 'Dolby'];

        return view('admin.G_salle', [
            'cinema' => $cinema,
            'salles' => $salles,
            'typesSalle' => $typesSalle,
        ]);
    }

    public function store(Request $request, $idCin)
    {
        $cinema = DB::table('cinema')->where('idCin', (int) $idCin)->first();

        if (!$cinema) {
            return redirect()->back()->withErrors(['error' => 'Cinéma introuvable.'])->withInput();
        }

        $typesAutorises = ['Normal', '3D', '4D', 'IMAX', 'XL', 'Premium', 'Dolby'];

        $request->validate([
            'nomSal' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Za-z0-9À-ÿ\s\-]+$/u',
            ],
            'nbSie' => [
                'required',
                'integer',
                'min:1',
                'max:500',
            ],
            'typSal' => [
                'required',
                'string',
                Rule::in($typesAutorises),
            ],
        ], [
            'nomSal.required' => 'Le nom de la salle est obligatoire.',
            'nomSal.min' => 'Le nom de la salle doit contenir au moins 2 caractères.',
            'nomSal.max' => 'Le nom de la salle ne doit pas dépasser 50 caractères.',
            'nomSal.regex' => 'Le nom de la salle ne doit contenir que des lettres, chiffres, espaces ou tirets.',

            'nbSie.required' => 'Le nombre de places est obligatoire.',
            'nbSie.integer' => 'Le nombre de places doit être un nombre entier.',
            'nbSie.min' => 'Le nombre de places doit être au minimum de 1.',
            'nbSie.max' => 'Le nombre de places ne peut pas dépasser 500.',

            'typSal.required' => 'Le type de salle est obligatoire.',
            'typSal.in' => 'Le type de salle sélectionné est invalide.',
        ]);

        $nomSal = trim((string) $request->nomSal);
        $nbSie = (int) $request->nbSie;
        $typSal = trim((string) $request->typSal);

        DB::table('salle')->insert([
            'nomSal' => $nomSal,
            'nbSie'  => $nbSie,
            'typSal' => $typSal,
            'idCin'  => (int) $idCin,
        ]);

        return redirect()->route('admin.salles.index', ['idCin' => $idCin])
            ->with('success', 'Salle ajoutée avec succès.');
    }

    public function update(Request $request, $idSal)
    {
        $salle = DB::table('salle')->where('idSal', (int) $idSal)->first();

        if (!$salle) {
            return redirect()->back()->withErrors(['error' => 'Salle introuvable.'])->withInput();
        }

        $typesAutorises = ['Normal', '3D', '4D', 'IMAX', 'XL', 'Premium', 'Dolby'];

        $request->validate([
            'nomSal' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[A-Za-z0-9À-ÿ\s\-]+$/u',
            ],
            'nbSie' => [
                'required',
                'integer',
                'min:1',
                'max:500',
            ],
            'typSal' => [
                'required',
                'string',
                Rule::in($typesAutorises),
            ],
        ], [
            'nomSal.required' => 'Le nom de la salle est obligatoire.',
            'nomSal.min' => 'Le nom de la salle doit contenir au moins 2 caractères.',
            'nomSal.max' => 'Le nom de la salle ne doit pas dépasser 50 caractères.',
            'nomSal.regex' => 'Le nom de la salle ne doit contenir que des lettres, chiffres, espaces ou tirets.',

            'nbSie.required' => 'Le nombre de places est obligatoire.',
            'nbSie.integer' => 'Le nombre de places doit être un nombre entier.',
            'nbSie.min' => 'Le nombre de places doit être au minimum de 1.',
            'nbSie.max' => 'Le nombre de places ne peut pas dépasser 500.',

            'typSal.required' => 'Le type de salle est obligatoire.',
            'typSal.in' => 'Le type de salle sélectionné est invalide.',
        ]);

        $nomSal = trim((string) $request->nomSal);
        $nbSie = (int) $request->nbSie;
        $typSal = trim((string) $request->typSal);

        DB::table('salle')
            ->where('idSal', (int) $idSal)
            ->update([
                'nomSal' => $nomSal,
                'nbSie'  => $nbSie,
                'typSal' => $typSal,
            ]);

        return redirect()->route('admin.salles.index', ['idCin' => $salle->idCin])
            ->with('success', 'Salle modifiée avec succès.');
    }

    public function destroy($idSal)
    {
        $salle = DB::table('salle')->where('idSal', (int) $idSal)->first();

        if (!$salle) {
            return redirect()->back()->withErrors(['error' => 'Salle introuvable.']);
        }

        DB::table('salle')->where('idSal', (int) $idSal)->delete();

        return redirect()->route('admin.salles.index', ['idCin' => $salle->idCin])
            ->with('success', 'Salle supprimée avec succès.');
    }
}
