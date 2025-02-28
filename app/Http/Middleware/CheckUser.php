<?php

// app/Http/Middleware/CheckUser.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUser
{
    public function handle(Request $request, Closure $next)
    {
        // Add your custom logic here
        return $next($request);
    }
}

