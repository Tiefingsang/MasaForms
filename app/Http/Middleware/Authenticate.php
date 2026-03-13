<?php
// app/Http/Middleware/Authenticate.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                throw new AuthenticationException('Unauthenticated.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
