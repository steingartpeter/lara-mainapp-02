<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeLoggedIn
{
    //<nn>
    // Important:
    // To be able to use this middleware, we have to register it in app/Http/Kernel.php
    //</nn>

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //<nn>
        // Without login, just redirect to HOME.1
        //</nn>
        if (auth()->check()) {
            return $next($request);
        }

        //<nn>
        // Basic operation => forward the request to the next middleware/controller.
        //</nn>
        return redirect('/')->with('failure', 'You must be logged in!');
    }
}
