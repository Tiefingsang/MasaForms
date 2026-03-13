<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Notifications\SubscriptionActivatedNotification;
use App\Notifications\SubscriptionExpiringNotification;
use App\Notifications\SubscriptionExpiredNotification;

class PlanController extends Controller
{
    /**
     * Afficher la liste des plans
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $currentPlan = auth()->check() ? auth()->user()->currentPlan()->first() : null;

        return view('plans.index', compact('plans', 'currentPlan'));
    }

    /**
     * Afficher les détails d'un plan
     */
    public function show($slug)
    {
        $plan = Plan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('plans.show', compact('plan'));
    }

    /**
     * S'abonner à un plan
     */
    public function subscribe(Request $request, $slug)
    {
        //dd($slug);
        $plan = Plan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = auth()->user();

        // Désactiver l'ancien abonnement
        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        // Créer le nouvel abonnement
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'subscription_type' => $request->type ?? 'monthly',
            'starts_at' => now(),
            'status' => 'active',
        ]);
        $user->notify(new SubscriptionActivatedNotification($subscription));

        // Rediriger vers la page de paiement
        return redirect()->route('payment.checkout', ['subscription' => $subscription->id]);
    }


    public function public()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('pages.pricing', compact('plans'));
    }
}
