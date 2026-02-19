<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('connexion');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $pass  = $request->input('password');

        // IMPORTANT : pas de bcrypt -> comparaison en clair (comme tu l’as demandé)
        $user = User::where('mailUti', $email)->first();

        if (!$user || $user->mdpUti !== $pass) {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->withInput();
        }

        // Session (connexion)
        $request->session()->put('user_id', $user->idUti);
        $request->session()->put('user_role', $user->idRolUti);
        $request->session()->put('user_name', $user->preUti);

        // Redirection : Admin -> gestionnaires / Utilisateur -> catalogue
        if ((int) $user->idRolUti === 1) {
            return redirect('/admin'); // garde ta redirection admin actuelle
        }

        return redirect('/catalogue');
    }

    public function showRegister()
    {
        return view('creer_compte');
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom'                  => ['required', 'string', 'max:50'],
            'nom'                     => ['required', 'string', 'max:50'],
            'email'                   => ['required', 'email', 'max:255'],
            'password'                => ['required', 'string', 'min:3'],
            'password_confirmation'   => ['required', 'same:password'],
        ]);

        // Vérifie l’unicité sur mailUti (ton schéma)
        $exists = User::where('mailUti', $request->input('email'))->exists();
        if ($exists) {
            return back()->withErrors([
                'email' => 'Cet email est déjà utilisé.',
            ])->withInput();
        }

        // Création utilisateur (idRolUti = 2 => Utilisateur)
        $user = User::create([
            'nomUti'    => $request->input('nom'),
            'preUti'    => $request->input('prenom'),
            'mailUti'   => $request->input('email'),
            'mdpUti'    => $request->input('password'), // en clair (comme demandé)
            'datInsUti' => now(),
            'idRolUti'  => 2,
        ]);

        // Connexion auto après inscription (utilisateur)
        $request->session()->put('user_id', $user->idUti);
        $request->session()->put('user_role', $user->idRolUti);
        $request->session()->put('user_name', $user->preUti);

        return redirect('/catalogue');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/connexion');
    }
}
