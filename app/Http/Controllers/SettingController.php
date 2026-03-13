<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingController extends Controller // <-- Bien étendre Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher les paramètres généraux
     */
    public function index()
    {
        $user = auth()->user();
        $settings = $user->settings ?? [];

        return view('settings.index', compact('user', 'settings'));
    }

    /**
     * Mettre à jour les paramètres généraux
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'timezone' => ['required', 'string', 'timezone'],
            'language' => ['required', 'string', 'in:fr,en'],
            'date_format' => ['required', 'string', 'in:d/m/Y,Y-m-d,m/d/Y'],
            'items_per_page' => ['required', 'integer', 'min:10', 'max:100'],
        ]);

        $settings = $user->settings ?? [];
        $settings['general'] = $request->only(['timezone', 'language', 'date_format', 'items_per_page']);

        $user->update(['settings' => $settings]);

        return redirect()->route('settings.index')
            ->with('success', 'Paramètres généraux mis à jour avec succès.');
    }

    /**
     * Afficher les préférences
     */
    public function preferences()
    {
        $user = auth()->user();
        $preferences = $user->settings['preferences'] ?? [
            'dashboard_charts' => true,
            'email_notifications' => true,
            'sound_notifications' => false,
            'compact_mode' => false,
        ];

        return view('settings.preferences', compact('user', 'preferences'));
    }

    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'dashboard_charts' => ['boolean'],
            'email_notifications' => ['boolean'],
            'sound_notifications' => ['boolean'],
            'compact_mode' => ['boolean'],
        ]);

        $settings = $user->settings ?? [];
        $settings['preferences'] = $request->only([
            'dashboard_charts',
            'email_notifications',
            'sound_notifications',
            'compact_mode'
        ]);

        $user->update(['settings' => $settings]);

        return redirect()->route('settings.preferences')
            ->with('success', 'Préférences mises à jour avec succès.');
    }

    /**
     * Afficher les paramètres de notification
     */
    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->settings['notifications'] ?? [
            'new_response' => true,
            'subscription_expiring' => true,
            'form_limit' => true,
            'weekly_report' => false,
            'monthly_report' => true,
        ];

        return view('settings.notifications', compact('user', 'notifications'));
    }

    /**
     * Mettre à jour les paramètres de notification
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'new_response' => ['boolean'],
            'subscription_expiring' => ['boolean'],
            'form_limit' => ['boolean'],
            'weekly_report' => ['boolean'],
            'monthly_report' => ['boolean'],
        ]);

        $settings = $user->settings ?? [];
        $settings['notifications'] = $request->only([
            'new_response',
            'subscription_expiring',
            'form_limit',
            'weekly_report',
            'monthly_report'
        ]);

        $user->update(['settings' => $settings]);

        return redirect()->route('settings.notifications')
            ->with('success', 'Paramètres de notification mis à jour avec succès.');
    }

    /**
     * Afficher la gestion de l'équipe (pour plans Business)
     */
    public function team()
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a accès à la gestion d'équipe
        $plan = $user->currentPlan()->first();
        if (!$plan || !$plan->has_multi_users) {
            return redirect()->route('plans.index')
                ->with('error', 'La gestion d\'équipe est disponible uniquement sur le plan Business.');
        }

        $teamMembers = $user->teamMembers ?? collect(); // À adapter selon votre logique

        return view('settings.team', compact('user', 'teamMembers'));
    }

    /**
     * Inviter un membre dans l'équipe
     */
    public function inviteTeamMember(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'string', 'in:admin,editor,viewer'],
        ]);

        // Logique d'invitation à implémenter
        // ...

        return redirect()->route('settings.team')
            ->with('success', 'Invitation envoyée avec succès.');
    }

    /**
     * Retirer un membre de l'équipe
     */
    public function removeTeamMember($userId)
    {
        // Logique de suppression à implémenter
        // ...

        return redirect()->route('settings.team')
            ->with('success', 'Membre retiré de l\'équipe.');
    }

    /**
     * Afficher la gestion des API
     */
    public function api()
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a accès à l'API
        $plan = $user->currentPlan()->first();
        if (!$plan || !$plan->has_api_access) {
            return redirect()->route('plans.index')
                ->with('error', 'L\'accès API est disponible uniquement sur le plan Business.');
        }

        $apiKeys = $user->apiKeys ?? collect(); // À adapter selon votre logique

        return view('settings.api', compact('user', 'apiKeys'));
    }

    /**
     * Créer une nouvelle clé API
     */
    public function createApiKey(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Logique de création de clé API à implémenter
        // ...

        return redirect()->route('settings.api')
            ->with('success', 'Clé API créée avec succès.');
    }

    /**
     * Révoquer une clé API
     */
    public function revokeApiKey($keyId)
    {
        // Logique de révocation à implémenter
        // ...

        return redirect()->route('settings.api')
            ->with('success', 'Clé API révoquée avec succès.');
    }

    /**
     * Afficher les paramètres de facturation
     */
    public function billing()
    {
        $user = auth()->user();
        $invoices = $user->payments()->orderBy('created_at', 'desc')->paginate(10);

        return view('settings.billing', compact('user', 'invoices'));
    }

    /**
     * Mettre à jour la carte de paiement
     */
    public function updateCard(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        // Logique de mise à jour de carte à implémenter
        // ...

        return redirect()->route('settings.billing')
            ->with('success', 'Moyen de paiement mis à jour avec succès.');
    }

    /**
     * Télécharger une facture
     */
    public function downloadInvoice($invoiceId)
    {
        // Logique de téléchargement de facture à implémenter
        // ...

        return redirect()->route('settings.billing');
    }

    /**
     * Exporter les données personnelles
     */
    public function exportData()
    {
        $user = auth()->user();

        $data = [
            'user' => $user->toArray(),
            'forms' => $user->forms()->with('fields', 'responses')->get()->toArray(),
        ];

        $filename = 'masadigitale-export-' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Demander l'export des données
     */
    public function requestDataExport(Request $request)
    {
        // Logique d'export à implémenter (job en arrière-plan)
        // ...

        return redirect()->route('settings.index')
            ->with('success', 'Votre demande d\'export a été enregistrée. Vous recevrez un email dès que vos données seront prêtes.');
    }

    /**
     * Supprimer le compte (redirige vers la page de profil)
     */
    public function deleteAccount()
    {
        return redirect()->route('profile.edit')
            ->with('warning', 'Pour supprimer votre compte, veuillez vous rendre dans la section "Zone de danger" de votre profil.');
    }
}
