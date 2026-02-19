<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('connexion');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Table users (IMPORTANT)
        $user = DB::table('users')
            ->where('mailUti', $request->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'Email ou mot de passe incorrect.');
        }

        // Mot de passe (pas hashé chez toi, c’est du texte brut "1234")
        if ($request->password !== $user->mdpUti) {
            return back()->with('error', 'Email ou mot de passe incorrect.');
        }

        // Vérifier rôle
        $isAdmin = ($user->idRolUti == 1); // 1 = admin chez toi

        session([
            'user' => [
                'id' => $user->idUti,
                'email' => $user->mailUti,
                'prenom' => $user->preUti,
                'nom' => $user->nomUti,
                'is_admin' => $isAdmin,
            ]
        ]);

        if ($isAdmin) {
            return redirect('/admin/G_film');
        }

        return redirect('/catalogue');
    }


    public function logout()
    {
        session()->forget('user');
        return redirect('/');
    }
}
