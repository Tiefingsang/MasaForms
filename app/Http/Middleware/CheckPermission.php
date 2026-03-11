<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

         if (!auth()->check()) {
            abort(403, 'Non autorisé');
        }

        if (!auth()->user()->can($permission)) {
            abort(403, 'Vous n\'avez pas la permission nécessaire');
        }

        return $next($request);
    }
}
