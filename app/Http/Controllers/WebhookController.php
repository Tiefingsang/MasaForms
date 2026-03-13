<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    /**
     * Gérer les webhooks entrants
     */
    public function handle(Request $request, $provider)
    {
        // Log de l'entrée pour déboguer
        Log::info('Webhook reçu', ['provider' => $provider, 'data' => $request->all()]);

        switch ($provider) {
            case 'orange_money':
                return $this->handleOrangeMoneyWebhook($request);

            case 'moov':
                return $this->handleMoovWebhook($request);

            case 'wave':
                return $this->handleWaveWebhook($request);

            case 'stripe':
                return $this->handleStripeWebhook($request);

            case 'paypal':
                return $this->handlePaypalWebhook($request);

            default:
                Log::warning('Fournisseur de webhook inconnu', ['provider' => $provider]);
                return response()->json(['error' => 'Unknown provider'], 400);
        }
    }

    /**
     * Gérer les webhooks Orange Money
     */
    private function handleOrangeMoneyWebhook(Request $request)
    {
        // Valider la signature du webhook
        if (!$this->validateOrangeMoneySignature($request)) {
            Log::error('Signature Orange Money invalide');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->all();

        try {
            DB::beginTransaction();

            // Chercher le paiement correspondant
            $payment = Payment::where('transaction_id', $data['transaction_id'])->first();

            if (!$payment) {
                Log::error('Paiement non trouvé', ['transaction_id' => $data['transaction_id']]);
                DB::rollBack();
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Mettre à jour le statut du paiement
            switch ($data['status']) {
                case 'SUCCESS':
                case 'COMPLETED':
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'provider_response' => $data
                    ]);

                    // Activer l'abonnement associé
                    if ($payment->subscription) {
                        $payment->subscription->update([
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => now()->addMonth(),
                        ]);
                    }
                    break;

                case 'FAILED':
                case 'CANCELLED':
                    $payment->update([
                        'status' => 'failed',
                        'provider_response' => $data
                    ]);
                    break;

                case 'PENDING':
                    $payment->update([
                        'status' => 'pending',
                        'provider_response' => $data
                    ]);
                    break;
            }

            DB::commit();

            Log::info('Webhook Orange Money traité avec succès', [
                'transaction_id' => $data['transaction_id'],
                'status' => $data['status']
            ]);

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement du webhook Orange Money', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Gérer les webhooks Moov
     */
    private function handleMoovWebhook(Request $request)
    {
        // Logique similaire à Orange Money
        Log::info('Webhook Moov reçu', $request->all());

        // À implémenter selon la documentation Moov
        return response()->json(['status' => 'received']);
    }

    /**
     * Gérer les webhooks Wave
     */
    private function handleWaveWebhook(Request $request)
    {
        // Logique similaire à Orange Money
        Log::info('Webhook Wave reçu', $request->all());

        // À implémenter selon la documentation Wave
        return response()->json(['status' => 'received']);
    }

    /**
     * Gérer les webhooks Stripe
     */
    private function handleStripeWebhook(Request $request)
    {
        // Logique pour Stripe
        Log::info('Webhook Stripe reçu', $request->all());

        // À implémenter selon la documentation Stripe
        return response()->json(['status' => 'received']);
    }

    /**
     * Gérer les webhooks PayPal
     */
    private function handlePaypalWebhook(Request $request)
    {
        // Logique pour PayPal
        Log::info('Webhook PayPal reçu', $request->all());

        // À implémenter selon la documentation PayPal
        return response()->json(['status' => 'received']);
    }

    /**
     * Valider la signature du webhook Orange Money
     */
    private function validateOrangeMoneySignature(Request $request)
    {
        // À implémenter selon la documentation Orange Money
        // Retourne true pour le moment (à des fins de test)
        return true;
    }

    /**
     * Webhook pour les intégrations (WhatsApp, Slack, etc.)
     */
    public function handleIntegrationWebhook(Request $request, $integrationId, $event)
    {
        Log::info('Webhook d\'intégration reçu', [
            'integration_id' => $integrationId,
            'event' => $event,
            'data' => $request->all()
        ]);

        $integration = Integration::find($integrationId);

        if (!$integration || !$integration->is_active) {
            return response()->json(['error' => 'Integration not found or inactive'], 404);
        }

        // Traiter l'événement selon le type d'intégration
        switch ($integration->type) {
            case 'whatsapp':
                return $this->handleWhatsAppWebhook($integration, $request, $event);

            case 'slack':
                return $this->handleSlackWebhook($integration, $request, $event);

            case 'mailchimp':
                return $this->handleMailchimpWebhook($integration, $request, $event);

            default:
                return response()->json(['error' => 'Unsupported integration type'], 400);
        }
    }

    /**
     * Gérer les webhooks WhatsApp
     */
    private function handleWhatsAppWebhook(Integration $integration, Request $request, $event)
    {
        // Logique pour WhatsApp
        Log::info('Webhook WhatsApp traité', [
            'integration' => $integration->name,
            'event' => $event
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Gérer les webhooks Slack
     */
    private function handleSlackWebhook(Integration $integration, Request $request, $event)
    {
        // Logique pour Slack
        Log::info('Webhook Slack traité', [
            'integration' => $integration->name,
            'event' => $event
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Gérer les webhooks Mailchimp
     */
    private function handleMailchimpWebhook(Integration $integration, Request $request, $event)
    {
        // Logique pour Mailchimp
        Log::info('Webhook Mailchimp traité', [
            'integration' => $integration->name,
            'event' => $event
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Webhook de test pour vérifier que le système fonctionne
     */
    public function test(Request $request)
    {
        Log::info('Webhook de test reçu', $request->all());

        return response()->json([
            'status' => 'ok',
            'message' => 'Webhook test successful',
            'received_at' => now()->toIso8601String(),
            'data' => $request->all()
        ]);
    }
}
