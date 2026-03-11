<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Afficher la liste des templates
     */
    public function index(Request $request)
    {
        $query = Template::where('is_active', true);

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $templates = $query->orderBy('usage_count', 'desc')->paginate(12);

        $categories = Template::select('category')
            ->distinct()
            ->pluck('category');

        return view('templates.index', compact('templates', 'categories'));
    }

    /**
     * Afficher un template
     */
    public function show($slug)
    {
        $template = Template::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

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

        // Vérifier si l'utilisateur peut créer un formulaire
        if (!auth()->user()->canCreateForm()) {
            return redirect()->route('plans.index')
                ->with('error', 'Vous avez atteint la limite de formulaires pour votre plan.');
        }

        // Vérifier si le template est premium et si l'utilisateur a un plan qui y donne accès
        if ($template->is_premium) {
            $plan = auth()->user()->currentPlan()->first();
            if (!$plan || !$plan->has_templates) {
                return redirect()->route('plans.index')
                    ->with('error', 'Ce template est premium. Veuillez passer à un plan supérieur.');
            }
        }

        // Créer le formulaire à partir du template
        $form = Form::create([
            'user_id' => auth()->id(),
            'title' => $template->name,
            'slug' => Str::random(10),
            'description' => $template->description,
        ]);

        // Appliquer la structure du template
        $template->applyToForm($form);

        return redirect()->route('forms.edit', $form)
            ->with('success', 'Template appliqué avec succès !');
    }
}