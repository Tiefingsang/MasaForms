<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFormLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user->canCreateForm()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Vous avez atteint la limite de formulaires pour votre plan.'
                ], 403);
            }

            return redirect()->route('plans.index')
                ->with('error', 'Vous avez atteint la limite de formulaires pour votre plan. Veuillez passer à un plan supérieur.');
        }

        return $next($request);
    }
}
