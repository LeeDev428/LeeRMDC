<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->usertype === 'admin') {
            return $next($request); // User is admin, proceed to AdminController
        }
        

        // Redirect if the user is not an admin
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}
