<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des clés API
     */
    public function index()
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a accès à l'API
        $plan = $user->currentPlan()->first();
        if (!$plan || !$plan->has_api_access) {
            return redirect()->route('plans.index')
                ->with('error', 'L\'accès API est disponible uniquement sur le plan Business.');
        }

        $apiKeys = $user->apiKeys()->orderBy('created_at', 'desc')->get();

        return view('api-keys.index', compact('apiKeys'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('api-keys.create');
    }

    /**
     * Créer une nouvelle clé API
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:read,write,delete,admin'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ]);

        $user = auth()->user();

        // Vérifier les limites du plan
        $plan = $user->currentPlan()->first();
        $currentKeysCount = $user->apiKeys()->count();

        if ($plan && $plan->max_api_keys && $currentKeysCount >= $plan->max_api_keys) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint la limite de clés API pour votre plan.');
        }

        // Générer la clé
        $plainTextKey = Str::random(40);

        $apiKey = ApiKey::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'key' => hash('sha256', $plainTextKey), // Stocker le hash
            'permissions' => $request->permissions ?? ['read'],
            'expires_at' => $request->expires_at,
            'is_active' => true,
        ]);

        // Afficher la clé en clair une seule fois
        return redirect()->route('api-keys.show', $apiKey)
            ->with('success', 'Clé API créée avec succès !')
            ->with('plain_text_key', $plainTextKey);
    }

    /**
     * Afficher les détails d'une clé API
     */
    public function show(ApiKey $apiKey)
    {
        // Vérifier que la clé appartient à l'utilisateur
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        return view('api-keys.show', compact('apiKey'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ApiKey $apiKey)
    {
        // Vérifier que la clé appartient à l'utilisateur
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        return view('api-keys.edit', compact('apiKey'));
    }

    /**
     * Mettre à jour une clé API
     */
    public function update(Request $request, ApiKey $apiKey)
    {
        // Vérifier que la clé appartient à l'utilisateur
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:read,write,delete,admin'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'is_active' => ['boolean'],
        ]);

        $apiKey->update([
            'name' => $request->name,
            'permissions' => $request->permissions ?? ['read'],
            'expires_at' => $request->expires_at,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('api-keys.index')
            ->with('success', 'Clé API mise à jour avec succès.');
    }

    /**
     * Révoquer une clé API
     */
    public function destroy(ApiKey $apiKey)
    {
        // Vérifier que la clé appartient à l'utilisateur
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        $apiKey->delete();

        return redirect()->route('api-keys.index')
            ->with('success', 'Clé API révoquée avec succès.');
    }

    /**
     * Régénérer une clé API
     */
    public function regenerate(ApiKey $apiKey)
    {
        // Vérifier que la clé appartient à l'utilisateur
        if ($apiKey->user_id !== auth()->id()) {
            abort(403);
        }

        $plainTextKey = Str::random(40);

        $apiKey->update([
            'key' => hash('sha256', $plainTextKey),
        ]);

        return redirect()->route('api-keys.show', $apiKey)
            ->with('success', 'Clé API régénérée avec succès !')
            ->with('plain_text_key', $plainTextKey);
    }

    /**
     * Tester une clé API
     */
    public function test(Request $request)
    {
        $request->validate([
            'api_key' => ['required', 'string'],
        ]);

        $hashedKey = hash('sha256', $request->api_key);
        $apiKey = ApiKey::where('key', $hashedKey)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Clé API invalide ou inactive',
            ], 401);
        }

        if ($apiKey->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Clé API expirée',
            ], 401);
        }

        // Mettre à jour la date de dernière utilisation
        $apiKey->markAsUsed();

        return response()->json([
            'success' => true,
            'message' => 'Clé API valide',
            'data' => [
                'name' => $apiKey->name,
                'permissions' => $apiKey->permissions,
                'expires_at' => $apiKey->expires_at,
            ]
        ]);
    }

    /**
     * Statistiques d'utilisation des clés API
     */
    public function statistics()
    {
        $user = auth()->user();

        $stats = [
            'total_keys' => $user->apiKeys()->count(),
            'active_keys' => $user->apiKeys()->where('is_active', true)->count(),
            'expired_keys' => $user->apiKeys()->where('expires_at', '<', now())->count(),
            'recently_used' => $user->apiKeys()
                ->whereNotNull('last_used_at')
                ->where('last_used_at', '>', now()->subDays(30))
                ->count(),
        ];

        return view('api-keys.statistics', compact('stats'));
    }
}
