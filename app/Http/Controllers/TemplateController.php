<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Notifications\NewTemplateNotification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Constructeur avec middlewares
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['public', 'publicShow']);
        $this->middleware('admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    // ===========================================
    // MÉTHODES PUBLIQUES (accessibles sans authentification)
    // ===========================================

    /**
     * Afficher la liste des templates publics
     */
    public function public(Request $request)
    {
        $query = Template::where('is_active', true);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->premium !== null) {
            $query->where('is_premium', $request->premium);
        }

        $templates = $query->orderBy('usage_count', 'desc')->paginate(12);

        $categories = Template::select('category')
            ->distinct()
            ->where('is_active', true)
            ->pluck('category');

        return view('templates.public', compact('templates', 'categories'));
    }

    /**
     * Afficher un template public
     */
    public function publicShow($slug)
    {
        $template = Template::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('templates.public-show', compact('template'));
    }

    // ===========================================
    // MÉTHODES POUR UTILISATEURS CONNECTÉS
    // ===========================================

    /**
     * Afficher la liste des templates (espace membre)
     */
    public function index(Request $request)
    {
        $query = Template::where('is_active', true);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrer selon le plan de l'utilisateur
        $user = auth()->user();
        $plan = $user->currentPlan()->first();

        if (!$plan || !$plan->has_templates) {
            // Si l'utilisateur n'a pas accès aux templates premium, ne montrer que les gratuits
            $query->where('is_premium', false);
        }

        $templates = $query->orderBy('is_premium', 'asc')
                          ->orderBy('usage_count', 'desc')
                          ->paginate(12);

        $categories = Template::select('category')
            ->distinct()
            ->where('is_active', true)
            ->pluck('category');

        $userPlan = $plan;

        return view('templates.index', compact('templates', 'categories', 'userPlan'));
    }

    /**
     * Afficher un template
     */
    public function show($slug)
    {
        $template = Template::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Vérifier si l'utilisateur a accès aux templates premium
        if ($template->is_premium) {
            $plan = auth()->user()->currentPlan()->first();
            if (!$plan || !$plan->has_templates) {
                return redirect()->route('plans.index')
                    ->with('error', 'Ce template est premium. Veuillez passer à un plan supérieur pour y accéder.');
            }
        }

        return view('templates.show', compact('template'));
    }

    /**
     * Appliquer un template à un nouveau formulaire
     */
    public function apply(Request $request, $slug)
    {
        $template = Template::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $user = auth()->user();

        // Vérifier si l'utilisateur peut créer un formulaire
        if (!$user->canCreateForm()) {
            return redirect()->route('plans.index')
                ->with('error', 'Vous avez atteint la limite de formulaires pour votre plan.');
        }

        // Vérifier si le template est premium et si l'utilisateur a un plan qui y donne accès
        if ($template->is_premium) {
            $plan = $user->currentPlan()->first();
            if (!$plan || !$plan->has_templates) {
                return redirect()->route('plans.index')
                    ->with('error', 'Ce template est premium. Veuillez passer à un plan supérieur.');
            }
        }

        // Créer le formulaire à partir du template
        $form = Form::create([
            'user_id' => $user->id,
            'title' => $template->name,
            'slug' => Str::random(10),
            'description' => $template->description,
            'settings' => [
                'from_template' => $template->id,
                'template_name' => $template->name
            ]
        ]);

        // Appliquer la structure du template
        $template->applyToForm($form);

        // Incrémenter le compteur d'utilisation
        $template->increment('usage_count');

        // Rediriger vers l'édition du formulaire
        return redirect()->route('forms.edit', $form)
            ->with('success', 'Template appliqué avec succès ! Vous pouvez maintenant personnaliser votre formulaire.');
    }

    /**
     * Afficher les templates sauvegardés par l'utilisateur
     */
    public function myTemplates()
    {
        $user = auth()->user();

        // Si vous avez une table user_templates pour les favoris
        // $templates = $user->favoriteTemplates()->paginate(12);

        // Pour l'instant, on retourne une vue simple
        return view('templates.my-templates');
    }

    /**
     * Sauvegarder un template dans les favoris
     */
    public function favorite(Template $template)
    {
        $user = auth()->user();

        // Implémentez la logique de favoris ici
        // $user->favoriteTemplates()->attach($template->id);

        return response()->json(['success' => true]);
    }

    /**
     * Retirer un template des favoris
     */
    public function unfavorite(Template $template)
    {
        $user = auth()->user();

        // $user->favoriteTemplates()->detach($template->id);

        return response()->json(['success' => true]);
    }

    // ===========================================
    // MÉTHODES ADMIN (CRUD complet)
    // ===========================================

    /**
     * Afficher la liste des templates (admin)
     */
    public function adminIndex(Request $request)
    {
        $query = Template::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->premium !== null) {
            $query->where('is_premium', $request->premium);
        }

        if ($request->active !== null) {
            $query->where('is_active', $request->active);
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = Template::select('category')->distinct()->pluck('category');

        return view('admin.templates.index', compact('templates', 'categories'));
    }

    /**
     * Afficher le formulaire de création d'un template
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Enregistrer un nouveau template
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'structure' => 'required|json',
            'is_premium' => 'boolean',
        ]);

        $data = $request->except('thumbnail');

        // Générer le slug
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);

        // Traiter l'image
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('templates', 'public');
            $data['thumbnail'] = $path;
        }

        $template = Template::create($data);

        // Notifier tous les utilisateurs du nouveau template (sauf si premium)
        if (!$template->is_premium) {
            $this->notifyUsersOfNewTemplate($template);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template créé avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'un template
     */
    public function edit(Template $template)
    {
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Mettre à jour un template
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'thumbnail' => 'nullable|image|max:2048',
            'structure' => 'required|json',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('thumbnail');

        // Mettre à jour le slug si le nom change
        if ($template->name !== $request->name) {
            $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
        }

        // Traiter la nouvelle image
        if ($request->hasFile('thumbnail')) {
            // Supprimer l'ancienne image
            if ($template->thumbnail) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $path = $request->file('thumbnail')->store('templates', 'public');
            $data['thumbnail'] = $path;
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template mis à jour avec succès !');
    }

    /**
     * Dupliquer un template
     */
    public function duplicate(Template $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (copie)';
        $newTemplate->slug = Str::slug($template->name) . '-copy-' . Str::random(5);
        $newTemplate->usage_count = 0;
        $newTemplate->created_at = now();
        $newTemplate->save();

        return redirect()->route('admin.templates.edit', $newTemplate)
            ->with('success', 'Template dupliqué avec succès !');
    }

    /**
     * Supprimer un template
     */
    public function destroy(Template $template)
    {
        // Supprimer l'image
        if ($template->thumbnail) {
            Storage::disk('public')->delete($template->thumbnail);
        }

        $template->delete();

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template supprimé avec succès !');
    }

    /**
     * Activer/Désactiver un template
     */
    public function toggleStatus(Template $template)
    {
        $template->is_active = !$template->is_active;
        $template->save();

        return response()->json([
            'success' => true,
            'is_active' => $template->is_active
        ]);
    }

    /**
     * Basculer le statut premium d'un template
     */
    public function togglePremium(Template $template)
    {
        $wasPremium = $template->is_premium;
        $template->is_premium = !$template->is_premium;
        $template->save();

        // Si le template devient gratuit, notifier les utilisateurs
        if ($wasPremium && !$template->is_premium) {
            $this->notifyUsersOfNewTemplate($template);
        }

        return response()->json([
            'success' => true,
            'is_premium' => $template->is_premium
        ]);
    }

    /**
     * Prévisualiser un template
     */
    public function preview(Template $template)
    {
        return view('admin.templates.preview', compact('template'));
    }

    /**
     * Exporter un template (pour partage)
     */
    public function export(Template $template)
    {
        $data = [
            'name' => $template->name,
            'description' => $template->description,
            'category' => $template->category,
            'structure' => $template->structure,
            'is_premium' => $template->is_premium,
        ];

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="template-' . $template->slug . '.json"');
    }

    /**
     * Importer un template (depuis un fichier JSON)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048'
        ]);

        $content = file_get_contents($request->file('file')->path());
        $data = json_decode($content, true);

        $template = Template::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . Str::random(5),
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? 'Autre',
            'structure' => $data['structure'],
            'is_premium' => $data['is_premium'] ?? false,
            'is_active' => true,
        ]);

        return redirect()->route('admin.templates.edit', $template)
            ->with('success', 'Template importé avec succès !');
    }

    // ===========================================
    // MÉTHODES PRIVÉES
    // ===========================================

    /**
     * Notifier tous les utilisateurs d'un nouveau template
     */
    private function notifyUsersOfNewTemplate(Template $template)
    {
        try {
            $users = User::where('is_active', true)->get();

            foreach ($users->chunk(50) as $chunk) {
                foreach ($chunk as $user) {
                    $user->notify(new NewTemplateNotification($template));
                }
            }

            \Log::info("Notifications envoyées pour le template {$template->name}");
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'envoi des notifications: " . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des templates (pour admin)
     */
    public function statistics()
    {
        $stats = [
            'total' => Template::count(),
            'active' => Template::where('is_active', true)->count(),
            'premium' => Template::where('is_premium', true)->count(),
            'free' => Template::where('is_premium', false)->count(),
            'total_usage' => Template::sum('usage_count'),
            'most_used' => Template::orderBy('usage_count', 'desc')->take(5)->get(),
            'by_category' => Template::select('category')
                ->selectRaw('count(*) as total')
                ->groupBy('category')
                ->get()
        ];

        return view('admin.templates.statistics', compact('stats'));
    }
}
