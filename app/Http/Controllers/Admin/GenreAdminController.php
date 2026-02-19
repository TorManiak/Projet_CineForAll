<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreAdminController extends Controller
{
    public function index()
    {
        $genres = DB::table('genre')->orderBy('idGen', 'desc')->get();
        return view('admin.G_genre', compact('genres'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libGen' => ['required', 'string', 'max:100'],
        ]);

        DB::table('genre')->insert([
            'libGen' => $data['libGen'],
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre ajouté.');
    }

    public function update(Request $request, int $idGen)
    {
        $data = $request->validate([
            'libGen' => ['required', 'string', 'max:100'],
        ]);

        DB::table('genre')->where('idGen', $idGen)->update([
            'libGen' => $data['libGen'],
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre modifié.');
    }

    public function destroy(int $idGen)
    {
        DB::table('avoir')->where('idGen', $idGen)->delete();
        DB::table('genre')->where('idGen', $idGen)->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre supprimé.');
    }
}
