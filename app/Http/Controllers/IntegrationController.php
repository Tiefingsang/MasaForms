<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des intégrations
     */
    public function index()
    {
        $integrations = auth()->user()->integrations()->get();

        $availableIntegrations = [
            'whatsapp' => [
                'name' => 'WhatsApp',
                'description' => 'Recevez les réponses directement sur WhatsApp',
                'icon' => 'whatsapp.svg',
                'premium' => false,
            ],
            'slack' => [
                'name' => 'Slack',
                'description' => 'Recevez des notifications dans Slack',
                'icon' => 'slack.svg',
                'premium' => true,
            ],
            'mailchimp' => [
                'name' => 'Mailchimp',
                'description' => 'Ajoutez les répondants à vos listes Mailchimp',
                'icon' => 'mailchimp.svg',
                'premium' => true,
            ],
            'google_sheets' => [
                'name' => 'Google Sheets',
                'description' => 'Synchronisez les réponses avec Google Sheets',
                'icon' => 'sheets.svg',
                'premium' => true,
            ],
        ];

        return view('integrations.index', compact('integrations', 'availableIntegrations'));
    }

    /**
     * Configurer une intégration
     */
    public function configure(Request $request, $type)
    {
        $plan = auth()->user()->currentPlan()->first();

        // Vérifier si l'intégration est disponible dans le plan
        if ($type === 'whatsapp' && (!$plan || !$plan->has_whatsapp_integration)) {
            return redirect()->route('plans.index')
                ->with('error', 'L\'intégration WhatsApp n\'est pas disponible dans votre plan.');
        }

        return view('integrations.configure', compact('type'));
    }

    /**
     * Enregistrer une intégration
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'credentials' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $integration = Integration::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'type' => $request->type,
            'credentials' => $request->credentials,
            'settings' => $request->settings ?? [],
            'is_active' => true,
        ]);

        return redirect()->route('integrations.index')
            ->with('success', 'Intégration configurée avec succès !');
    }

    /**
     * Tester une intégration
     */
    public function test(Integration $integration)
    {
        $this->authorize('view', $integration);

        // Logique de test selon le type d'intégration
        $testResult = $this->testIntegration($integration);

        if ($testResult['success']) {
            return response()->json(['success' => true, 'message' => 'Intégration fonctionnelle']);
        }

        return response()->json(['success' => false, 'message' => $testResult['error']], 422);
    }

    /**
     * Supprimer une intégration
     */
    public function destroy(Integration $integration)
    {
        $this->authorize('delete', $integration);

        $integration->delete();

        return redirect()->route('integrations.index')
            ->with('success', 'Intégration supprimée avec succès');
    }

    private function testIntegration($integration)
    {
        // Implémenter les tests selon le type
        switch ($integration->type) {
            case 'whatsapp':
                // Tester API WhatsApp
                return ['success' => true];
            default:
                return ['success' => true];
        }
    }
}
