<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Ensure this line is present

class UserAppointment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated (logged in)
        if (!Auth::check()) { // Use Auth::check() instead of auth()->check()
            // Redirect to the login page or return an error response
            return redirect('/login')->with('error', 'You must be logged in to access appointments.');
        }

        // Optional: You can also add logic to check if the user has the necessary role or permission

        return $next($request); // Proceed to the next request
    }
}
