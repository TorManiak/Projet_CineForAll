<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('user')) {
            return redirect('/connexion')->with('error', 'Veuillez vous connecter.');
        }

        $user = session('user');

        if (!isset($user['is_admin']) || $user['is_admin'] !== true) {
            return redirect('/')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
