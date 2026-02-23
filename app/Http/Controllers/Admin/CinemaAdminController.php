<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CinemaAdminController extends Controller
{
    public function index()
    {
        $cinemas = DB::table('cinema')->orderBy('idCin', 'desc')->get();

        return view('admin.G_cine_salle', compact('cinemas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomCin' => ['required', 'string', 'max:120'],
            'adrCin' => ['nullable', 'string', 'max:255'],
            'vilCin' => ['required', 'string', 'max:120'],
            'cpCin' => ['nullable', 'string', 'max:10'],
            'maiCin' => ['nullable', 'email', 'max:120'],
            'telCin' => ['nullable', 'string', 'max:20'],
        ]);

        DB::table('cinema')->insert($data);

        return redirect()->route('admin.cinemas.index')->with('success', 'Cinéma ajouté.');
    }

    public function update(Request $request, int $idCin)
    {
        $data = $request->validate([
            'nomCin' => ['required', 'string', 'max:120'],
            'adrCin' => ['nullable', 'string', 'max:255'],
            'vilCin' => ['required', 'string', 'max:120'],
            'cpCin' => ['nullable', 'string', 'max:10'],
            'maiCin' => ['nullable', 'email', 'max:120'],
            'telCin' => ['nullable', 'string', 'max:20'],
        ]);

        DB::table('cinema')->where('idCin', $idCin)->update($data);

        return redirect()->route('admin.cinemas.index')->with('success', 'Cinéma modifié.');
    }

    public function destroy(int $idCin)
    {
        DB::table('cinema')->where('idCin', $idCin)->delete();

        return redirect()->route('admin.cinemas.index')->with('success', 'Cinéma supprimé.');
    }
}
