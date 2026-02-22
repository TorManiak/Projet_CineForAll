<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        if (!$user) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        // session('user') est un stdClass => accès via ->property
        // Admin = idRolUti == 1
        $roleId = isset($user->idRolUti) ? (int) $user->idRolUti : 0;

        if ($roleId !== 1) {
            return redirect('/')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
