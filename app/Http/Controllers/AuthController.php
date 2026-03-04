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

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $pass  = $request->input('password');

        // Table: users (colonnes: mailUti, mdpUti, idRolUti, etc.)
        $user = DB::table('users')->where('mailUti', $email)->first();

        // IMPORTANT : pas de bcrypt -> comparaison en clair
        if (!$user || !Hash::check($pass, $user->mdpUti))/*,$user->mdpUti !== $pass)*/ {
            return back()->withErrors([
                'email' => 'Identifiants incorrects.',
            ])->withInput();
        }

        Session::put('user', $user);

        // (Optionnel) un id simple
        Session::put('user_id', $user->idUti);

        // 1 = Admin ; 2 = Utilisateur ; 3 = Client (selon role_utilisateur)
        if ((int) $user->idRolUti === 1) {
            return redirect('/admin/G_film');
        }

        return redirect('/catalogue');
    }

    public function logout()
    {
        Session::forget('user');
        Session::forget('user_id');
        Session::flush();

        return redirect('/connexion');
    }

    public function showRegister()
    {
        return view('creer_compte');
    }

    public function register(Request $request)
    {
        $request->validate([
            'prenom'                => ['required', 'string', 'max:100'],
            'nom'                   => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:255'],
            'password'              => ['required', 'min:3', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        /*if ($request->input('password') !== $request->input('password_confirmation')) {
            return back()->withErrors([
                'password_confirmation' => 'Les mots de passe ne correspondent pas.',
            ])->withInput();
        }*/

        $email = $request->input('email');

        if (DB::table('users')->where('mailUti', $email)->exists()) {
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
