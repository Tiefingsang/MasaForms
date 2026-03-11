<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $subscription = $user->activeSubscription()->first();

        // Si pas d'abonnement actif, on attribue le plan gratuit automatiquement
        if (!$subscription) {
            $freePlan = \App\Models\Plan::where('slug', 'free')->first();
            if ($freePlan) {
                \App\Models\Subscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $freePlan->id,
                    'starts_at' => now(),
                    'status' => 'active',
                ]);
            }
        }

        return $next($request);
    }
}
