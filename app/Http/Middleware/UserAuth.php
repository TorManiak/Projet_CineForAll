<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Tu stockes l'utilisateur comme ça : Session::put('user', $user)
        if (!Session::has('user')) {
            return redirect('/connexion');
        }

        return $next($request);
    }
}
