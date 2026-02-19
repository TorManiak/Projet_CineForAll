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
            'email'    => ['required'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $pass  = $request->input('password');

        // IMPORTANT : ta table/colonnes => users.mailUti / users.mdpUti / users.idRolUti
        $user = User::where('mailUti', $email)->first();

        if (!$user || $user->mdpUti !== $pass) {
            return back()
                ->withErrors(['email' => 'Email ou mot de passe incorrect.'])
                ->withInput($request->only('email'));
        }

        // On repart sur la base qui marche (session "user" comme ton AuthController_Admin)
        $isAdmin = ((int) $user->idRolUti === 1);

        $request->session()->regenerate();

        session([
            'user' => [
                'id'       => $user->idUti,
                'email'    => $user->mailUti,
                'prenom'   => $user->preUti,
                'nom'      => $user->nomUti,
                'idRolUti' => (int) $user->idRolUti,
                'is_admin' => $isAdmin,
            ]
        ]);

        // ADMIN => gestion
        if ($isAdmin) {
            return redirect('/admin/G_film');
        }

        // UTILISATEUR => catalogue
        return redirect('/catalogue');
    }

    public function logout(Request $request)
    {
        session()->forget('user');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/connexion');
    }
}
