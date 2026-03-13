<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la page de checkout pour un abonnement
     */
    public function checkout(Subscription $subscription)
    {
        // Vérifier que l'abonnement appartient à l'utilisateur connecté
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $plan = $subscription->plan;

        return view('payment.checkout', compact('subscription', 'plan'));
    }

    /**
     * Traiter le paiement
     */
    public function process(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'required|string',
            'payment_provider' => 'required|string|in:orange_money,moov,moneygram,wave,card',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        // Vérifier que l'abonnement appartient à l'utilisateur
        if ($subscription->user_id !== auth()->id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            DB::beginTransaction();

            // Créer l'enregistrement de paiement
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'subscription_id' => $subscription->id,
                'amount' => $subscription->plan->price_monthly,
                'currency' => 'XOF',
                'payment_method' => $request->payment_method,
                'payment_provider' => $request->payment_provider,
                'status' => 'pending',
                'transaction_id' => uniqid('txn_'),
            ]);

            // Ici, vous intégreriez le vrai service de paiement
            // Par exemple : Orange Money API, Moov API, etc.

            // Simuler un paiement réussi pour le test
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Activer l'abonnement
            $subscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);

            DB::commit();

            // Envoyer une notification de paiement réussi
            // auth()->user()->notify(new PaymentSuccessfulNotification($payment));

            return redirect()->route('payment.success')
                ->with('success', 'Paiement effectué avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('payment.cancel')
                ->with('error', 'Erreur lors du paiement : ' . $e->getMessage());
        }
    }

    /**
     * Page de succès de paiement
     */
    public function success()
    {
        return view('payment.success');
    }

    /**
     * Page d'annulation de paiement
     */
    public function cancel()
    {
        return view('payment.cancel');
    }

    /**
     * Afficher les méthodes de paiement disponibles
     */
    public function methods()
    {
        $user = auth()->user();

        $paymentMethods = $user->payments()
            ->select('payment_method', 'payment_provider')
            ->distinct()
            ->get();

        return view('payment.methods', compact('paymentMethods'));
    }

    /**
     * Ajouter une méthode de paiement
     */
    public function addMethod(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'payment_provider' => 'required|string',
            'details' => 'required|array',
        ]);

        // Logique pour ajouter une méthode de paiement
        // À implémenter selon vos besoins

        return redirect()->route('payment.methods')
            ->with('success', 'Méthode de paiement ajoutée avec succès.');
    }

    /**
     * Supprimer une méthode de paiement
     */
    public function removeMethod($methodId)
    {
        // Logique pour supprimer une méthode de paiement
        // À implémenter selon vos besoins

        return redirect()->route('payment.methods')
            ->with('success', 'Méthode de paiement supprimée avec succès.');
    }

    /**
     * Historique des paiements
     */
    public function history()
    {
        $user = auth()->user();

        $payments = $user->payments()
            ->with('subscription.plan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('payment.history', compact('payments'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.show', compact('payment'));
    }

    /**
     * Télécharger une facture
     */
    public function downloadInvoice(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        // Logique pour générer et télécharger la facture PDF
        // À implémenter avec une bibliothèque comme barryvdh/laravel-dompdf

        // Exemple simple de retour JSON pour le moment
        return response()->json([
            'payment' => $payment,
            'message' => 'Facture en cours de génération...'
        ]);
    }

    /**
     * Webhook pour les notifications de paiement
     */
    public function webhook(Request $request, $provider)
    {
        // Logique pour gérer les webhooks des fournisseurs de paiement
        // À implémenter selon l'API de chaque fournisseur

        return response()->json(['status' => 'received']);
    }
}
