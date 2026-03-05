<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function store(Request $request, $film)
    {
        $request->validate([
            'note' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $user = session('user');
        $idUti = ($user && isset($user->idUti)) ? (int) $user->idUti : 0;

        if ($idUti <= 0) {
            return redirect()->route('login')->withErrors([
                'auth' => 'Vous devez être connecté pour noter un film.',
            ]);
        }

        DB::table('note')->updateOrInsert(
            [
                'idFil' => (int) $film,
                'idUti' => $idUti,
            ],
            [
                'note' => (int) $request->input('note'),
            ]
        );

        return back()->with('success', 'Votre note a bien été enregistrée.');
    }
}
