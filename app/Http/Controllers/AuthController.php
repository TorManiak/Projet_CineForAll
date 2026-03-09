<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('connexion');
    }

    public function login(Request $request) // Méthode appelée quand le formulaire de connexion est envoyé
    {
        $request->validate([ // Validation automatique des données envoyées par le formulaire
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email'); // Récupère les valeurs des champs envoyé par le formulaire
        $pass  = $request->input('password');

        $user = DB::table('users')->where('mailUti', $email)->first();// first = premier résultat en bdd trouver

        if (!$user || !Hash::check($pass, $user->mdpUti)){ //vérifier existance de l'uti + hashage de mdp en base
            return back()->withErrors([
                'email' => 'Identifiants ou mot de passe incorrects.',
            ])->withInput();//garder les valurs passer en paramètres
        }

        Session::put('user', $user);
        // Stock uti dans la session

        Session::put('user_id', $user->idUti);
        // Stock seulement l'id de l'uti dans la session

        if ((int) $user->idRolUti === 1) {

            return redirect('/admin/G_film');
        }

        return redirect('/catalogue');
    }

    public function logout()
    {
        Session::forget('user');
        // Supprimé la session de l'uti

        Session::forget('user_id');
        // Supprimé l'id de la session

        Session::flush();
        // Vidé données de seesion

        return redirect('/connexion');
    }

    public function showRegister() //Inscription
    {
        return view('creer_compte');
    }

    public function register(Request $request) //Fromulaire insciption
    {
        $request->validate([
            'prenom'                => ['required', 'string', 'max:100'],
            'nom'                   => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255'],
            'password'              => ['required', 'min:3', 'confirmed'],
            'password_confirmation' => ['required'],

        ]);


        $email = $request->input('email');

        if (DB::table('users')->where('mailUti', $email)->exists()) {
            // Verifié si pas de doublon en bdd

            return back()->withErrors([
                'email' => 'Cet email est déjà utilisé.',
            ])->withInput();
        }

        $id = DB::table('users')->insertGetId([
            'nomUti'    => $request->input('nom'),
            'preUti'    => $request->input('prenom'),
            'mdpUti'    => Hash::make($request->input('password')),
            'datInsUti' => now(),
            'mailUti'   => $email,
            'idRolUti'  => 2,
        ]);

        $user = DB::table('users')->where('idUti', $id)->first();

        Session::put('user', $user);

        Session::put('user_id', $user->idUti);

        return redirect('/catalogue');
    }
}
