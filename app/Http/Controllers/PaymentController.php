<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessfulNotification;
use App\Notifications\SubscriptionActivatedNotification;

// PayDunya
use Paydunya\Setup;
use Paydunya\Checkout\Store;
use Paydunya\Checkout\CheckoutInvoice;

class PaymentController extends Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');

        // Configuration PayDunya
        Setup::setMasterKey(config('paydunya.master_key'));
        Setup::setPublicKey(config('paydunya.public_key'));
        Setup::setPrivateKey(config('paydunya.private_key'));
        Setup::setToken(config('paydunya.token'));
        Setup::setMode(config('paydunya.mode', 'test'));

        // Configuration de la boutique
        Store::setName(config('paydunya.store.name', config('app.name')));
        Store::setTagline(config('paydunya.store.tagline', 'Formulaires en ligne simples et puissants'));
        Store::setPhoneNumber(config('paydunya.store.phone'));
        Store::setLogoUrl(config('paydunya.store.logo_url'));
        Store::setCallbackUrl(config('paydunya.store.callback_url', route('payment.webhook')));
    }

    /**
     * Afficher la page de sélection du mode de paiement pour un plan
     */
    public function selectMethod(Plan $plan, $interval = 'monthly')
{
    // Si c'est le plan gratuit, on active directement sans paiement
    if ($plan->price_monthly == 0 || $plan->slug === 'free') {
        return $this->activateFreePlan($plan, $interval);
    }

    return view('payment.select-method', compact('plan', 'interval'));
}

private function activateFreePlan(Plan $plan, $interval)
{
    try {
        DB::transaction(function () use ($plan, $interval) {
            // Désactiver les anciens abonnements
            Subscription::where('user_id', auth()->id())
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            // Créer le nouvel abonnement gratuit
            $subscription = Subscription::create([
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'subscription_type' => $interval,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => $interval === 'yearly' ? now()->addYear() : now()->addMonth(),
            ]);

            // Mettre à jour le plan de l'utilisateur
            $user = auth()->user();
            $user->plan_id = $plan->id;
            $user->save();

            // Créer un enregistrement de paiement symbolique
            Payment::create([
                'user_id' => auth()->id(),
                'subscription_id' => $subscription->id,
                'amount' => 0,
                'currency' => 'XOF',
                'payment_method' => 'free',
                'payment_provider' => 'free',
                'status' => 'completed',
                'paid_at' => now(),
                'transaction_id' => 'free_' . uniqid(),
            ]);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Plan gratuit activé avec succès !');

    } catch (\Exception $e) {
        Log::error('Erreur activation plan gratuit: ' . $e->getMessage());
        return redirect()->route('plans.index')
            ->with('error', 'Erreur lors de l\'activation du plan gratuit.');
    }
}

    /**
     * Initialiser un paiement avec PayDunya
     */
    public function initiate(Request $request, Plan $plan)
{
    // Vérifier que ce n'est pas le plan gratuit
    if ($plan->price_monthly == 0 || $plan->slug === 'free') {
        // Activer directement le plan gratuit
        try {
            DB::transaction(function () use ($plan) {
                // Désactiver les anciens abonnements
                Subscription::where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->update(['status' => 'cancelled']);

                // Créer le nouvel abonnement gratuit
                $subscription = Subscription::create([
                    'user_id' => auth()->id(),
                    'plan_id' => $plan->id,
                    'subscription_type' => 'monthly',
                    'status' => 'active',
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                ]);

                // Mettre à jour le plan de l'utilisateur
                $user = auth()->user();
                $user->plan_id = $plan->id;
                $user->save();

                // Créer un enregistrement de paiement symbolique
                Payment::create([
                    'user_id' => auth()->id(),
                    'subscription_id' => $subscription->id,
                    'amount' => 0,
                    'currency' => 'XOF',
                    'payment_method' => 'free',
                    'payment_provider' => 'free',
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_id' => 'free_' . uniqid(),
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', 'Plan gratuit activé avec succès !');

        } catch (\Exception $e) {
            Log::error('Erreur activation plan gratuit: ' . $e->getMessage());
            return redirect()->route('plans.index')
                ->with('error', 'Erreur lors de l\'activation du plan gratuit.');
        }
    }

    // Validation pour les plans payants
    $request->validate([
        'interval' => 'required|in:monthly,yearly'
    ]);

    $interval = $request->interval;
    $rawAmount = $interval === 'monthly' ? $plan->price_monthly : $plan->price_yearly;

    // Nettoyer et convertir le montant
    $amount = (int) floor((float) $rawAmount);

    // Vérifier le montant minimum PayDunya (200 FCFA)
    if ($amount < 200) {
        return back()->with('error', 'Le montant minimum pour un paiement est de 200 FCFA.');
    }

    try {
        // Créer une transaction en base
        $payment = Payment::create([
            'user_id' => auth()->id(),
            'subscription_id' => null,
            'amount' => $amount,
            'currency' => 'XOF',
            'payment_method' => 'paydunya',
            'payment_provider' => 'paydunya',
            'status' => 'pending',
            'transaction_id' => uniqid('txn_'),
        ]);

        // Créer la facture PayDunya
        $invoice = new CheckoutInvoice();

        // Montant total
        $invoice->setTotalAmount($amount);

        // Description
        $invoice->setDescription($plan->getPaymentDescription($interval));

        // Articles - AVEC 5 PARAMÈTRES
        $invoice->addItem(
            $plan->name,                    // 1. Nom du produit
            1,                               // 2. Quantité
            $amount,                         // 3. Prix unitaire
            $amount,                         // 4. Prix total
            $plan->description ?? "Abonnement {$plan->name}"  // 5. Description
        );

        // URLs de retour
        $invoice->setReturnUrl(route('payment.success', ['payment_id' => $payment->id]));
        $invoice->setCancelUrl(route('payment.cancel'));

        // Données personnalisées
        $invoice->addCustomData('payment_id', $payment->id);
        $invoice->addCustomData('user_id', auth()->id());
        $invoice->addCustomData('plan_id', $plan->id);
        $invoice->addCustomData('interval', $interval);

        // Créer la facture
        if ($invoice->create()) {
            // Mettre à jour la transaction
            $payment->update([
                'gateway_transaction_id' => $invoice->token,
                'payment_url' => $invoice->getInvoiceUrl(),
            ]);

            return redirect($invoice->getInvoiceUrl());
        } else {
            Log::error('Erreur PayDunya: ' . $invoice->response_text);
            return back()->with('error', 'Erreur de paiement: ' . $invoice->response_text);
        }

    } catch (\Exception $e) {
        Log::error('Erreur initiation: ' . $e->getMessage());
        return back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}

    /**
     * Page de succès après paiement
     */
   public function success(Request $request)
{
    $paymentId = $request->get('payment_id');

    if (!$paymentId) {
        return redirect()->route('dashboard')
            ->with('error', 'Paiement non trouvé');
    }

    try {
        // Récupérer la transaction avec l'utilisateur
        $payment = Payment::with('user')->find($paymentId);

        if (!$payment) {
            return redirect()->route('dashboard')
                ->with('error', 'Transaction non trouvée');
        }

        // Vérifier que le paiement appartient à l'utilisateur
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        // Si le paiement est toujours en attente, le marquer comme complété
        if ($payment->status === 'pending') {
            DB::transaction(function () use ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                // Activer l'abonnement (et envoyer la notification)
                $this->activateSubscription($payment);
            });
        }

        return view('payment.success', compact('payment'));

    } catch (\Exception $e) {
        Log::error('Erreur callback succès: ' . $e->getMessage());
        return redirect()->route('dashboard')
            ->with('error', 'Erreur lors de la confirmation du paiement');
    }
}

    /**
     * Page d'annulation
     */
    public function cancel(Request $request)
    {
        $paymentId = $request->get('payment_id');

        if ($paymentId) {
            $payment = Payment::find($paymentId);
            if ($payment && $payment->status === 'pending') {
                $payment->update(['status' => 'cancelled']);
            }
        }

        return view('payment.cancel');
    }

    /**
     * Webhook pour les notifications PayDunya
     */
    public function webhook(Request $request)
{
    Log::info('Webhook PayDunya reçu', $request->all());

    try {
        $token = $request->get('token');
        $status = $request->get('status');

        $payment = Payment::where('gateway_transaction_id', $token)->first();

        if ($payment && $status === 'completed' && $payment->status === 'pending') {
            DB::transaction(function () use ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                $this->activateSubscription($payment);
            });

            Log::info('Paiement confirmé via webhook', ['payment_id' => $payment->id]);
        }

        return response()->json(['status' => 'success']);

    } catch (\Exception $e) {
        Log::error('Erreur webhook PayDunya: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Activer un abonnement après paiement réussi
     */
    private function activateSubscription($payment)
{
    // Récupérer le plan depuis le montant
    $planId = $this->determinePlanIdFromAmount($payment->amount);

    // Désactiver les anciens abonnements
    Subscription::where('user_id', $payment->user_id)
        ->where('status', 'active')
        ->update(['status' => 'cancelled']);

    // Créer le nouvel abonnement
    $subscription = Subscription::create([
        'user_id' => $payment->user_id,
        'plan_id' => $planId,
        'subscription_type' => 'monthly',
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
    ]);

    // Mettre à jour le plan de l'utilisateur - MAINTENANT AVEC LA COLONNE PLAN_ID
    $user = $payment->user;
    $user->plan_id = $planId;  // Cette ligne fonctionnera maintenant
    $user->save();

    // Lier le paiement à l'abonnement
    $payment->update(['subscription_id' => $subscription->id]);

    try {
        $user->notify(new \App\Notifications\SubscriptionActivatedNotification($subscription));

        Log::info('Notification d\'abonnement envoyée', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'plan' => $subscription->plan->name
        ]);
    } catch (\Exception $e) {
        Log::error('Erreur envoi notification abonnement: ' . $e->getMessage());
    }

    return $subscription;
}

private function determinePlanIdFromAmount($amount)
{
    $plans = Plan::all();

    foreach ($plans as $plan) {
        if ((float)$amount == (float)$plan->price_monthly) {
            return $plan->id;
        }
        if ((float)$amount == (float)$plan->price_yearly) {
            return $plan->id;
        }
    }

    // Plan par défaut (Gratuit)
    return Plan::where('slug', 'free')->first()->id ?? 1;
}


private function getPlanIdFromPayment($payment)
{
    // Si le paiement a un plan_id (Solution 2)
    if (isset($payment->plan_id)) {
        return $payment->plan_id;
    }

    // Sinon, déterminer le plan en fonction du montant
    $plans = Plan::all();

    foreach ($plans as $plan) {
        if ($payment->amount == $plan->price_monthly || $payment->amount == $plan->price_yearly) {
            return $plan->id;
        }
    }

    // Plan par défaut (Gratuit)
    return Plan::where('slug', 'free')->first()->id ?? 1;
}


    /**
     * Déterminer l'intervalle depuis le montant
     */
    private function determineIntervalFromPayment($payment)
    {
        if ($payment->amount == $payment->plan->price_monthly) {
            return 'monthly';
        } elseif ($payment->amount == $payment->plan->price_yearly) {
            return 'yearly';
        }
        return 'monthly'; // Par défaut
    }

    /**
     * Calculer la date de fin d'abonnement
     */
    private function calculateEndDate($interval)
    {
        return $interval === 'monthly' ? now()->addMonth() : now()->addYear();
    }

    // Vos méthodes existantes (history, show, downloadInvoice) restent identiques
    public function history()
    {
        $user = auth()->user();
        $payments = $user->payments()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('payment.history', compact('payments'));
    }

    public function show(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }
        return view('payment.show', compact('payment'));
    }

    public function downloadInvoice(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }
        // À implémenter avec dompdf
        return response()->json(['message' => 'Fonctionnalité à venir']);
    }
}
