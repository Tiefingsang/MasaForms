<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\FormLimitNotification;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des formulaires
     */
    public function index()
    {
        $forms = auth()->user()->forms()
            ->withCount('responses')
            ->latest()
            ->paginate(10);

        return view('forms.index', compact('forms'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Vérifier si l'utilisateur peut créer un formulaire
        if (!auth()->user()->canCreateForm()) {
            return redirect()->route('plans.index')
                ->with('error', 'Vous avez atteint la limite de formulaires pour votre plan. Veuillez passer à un plan supérieur.');
        }

        return view('forms.create');
    }

    /**
     * Enregistrer un nouveau formulaire
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $form = Form::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::random(10),
            'primary_color' => $request->primary_color ?? '#3B82F6',
            'background_color' => $request->background_color ?? '#FFFFFF',
            'settings' => $request->settings ?? [],
        ]);

        $user = auth()->user();
        $plan = $user->currentPlan()->first();

        if ($plan && $plan->max_forms) {
            $currentCount = $user->forms()->count();
            $percentage = ($currentCount / $plan->max_forms) * 100;


            if ($percentage >= 80 && $percentage < 100) {
                $user->notify(new FormLimitNotification($user, $currentCount, $plan->max_forms));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Formulaire créé avec succès',
            'form' => $form,
            'redirect' => route('forms.edit', $form)
        ]);
    }

    /**
     * Afficher un formulaire spécifique
     */
    public function show(Form $form)
    {
        $this->authorize('view', $form);

        return view('forms.show', compact('form'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Form $form)
    {
        $this->authorize('update', $form);

        return view('forms.edit', compact('form'));
    }

    /**
     * Mettre à jour un formulaire
     */
    public function update(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thank_you_message' => 'nullable|string',
            'primary_color' => 'nullable|string|max:7',
            'background_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'accepts_responses' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $form->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Formulaire mis à jour avec succès',
            'form' => $form
        ]);
    }

    /**
     * Supprimer un formulaire
     */
    public function destroy(Form $form)
    {
        $this->authorize('delete', $form);

        $form->delete();

        return redirect()->route('forms.index')
            ->with('success', 'Formulaire supprimé avec succès');
    }

    /**
     * Sauvegarder les champs du formulaire
     */
    public function saveFields(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $validator = Validator::make($request->all(), [
            'fields' => 'required|array',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.is_required' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Supprimer les anciens champs
        $form->fields()->delete();

        // Créer les nouveaux champs
        foreach ($request->fields as $index => $fieldData) {
            FormField::create([
                'form_id' => $form->id,
                'label' => $fieldData['label'],
                'name' => Str::slug($fieldData['label']) . '_' . Str::random(5),
                'type' => $fieldData['type'],
                'placeholder' => $fieldData['placeholder'] ?? null,
                'help_text' => $fieldData['help_text'] ?? null,
                'options' => $fieldData['options'] ?? null,
                'is_required' => $fieldData['is_required'] ?? false,
                'order' => $index,
                'validation_rules' => $fieldData['validation_rules'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Champs sauvegardés avec succès',
        ]);
    }

    /**
     * Dupliquer un formulaire
     */
    public function duplicate(Form $form)
    {
        $this->authorize('view', $form);

        if (!auth()->user()->canCreateForm()) {
            return redirect()->route('plans.index')
                ->with('error', 'Vous avez atteint la limite de formulaires pour votre plan.');
        }

        $newForm = $form->replicate();
        $newForm->title = $form->title . ' (copie)';
        $newForm->slug = Str::random(10);
        $newForm->current_responses = 0;
        $newForm->save();

        foreach ($form->fields as $field) {
            $newField = $field->replicate();
            $newField->form_id = $newForm->id;
            $newField->name = Str::slug($field->label) . '_' . Str::random(5);
            $newField->save();
        }

        return redirect()->route('forms.edit', $newForm)
            ->with('success', 'Formulaire dupliqué avec succès');
    }

    public function share(Form $form)
    {
        $this->authorize('view', $form);

        return view('forms.share', compact('form'));
    }


    public function regenerateLink(Form $form)
    {
        $this->authorize('update', $form);

        $form->slug = Str::random(10);
        $form->save();

        return redirect()->route('forms.share', $form)
            ->with('success', 'Nouveau lien généré avec succès !');
    }


    public function updateSettings(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_public' => 'boolean',
            'accepts_responses' => 'boolean',
            'show_progress_bar' => 'boolean',
            'captcha_enabled' => 'boolean',
            'max_responses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'password' => 'nullable|string|min:4',
            'thank_you_message' => 'nullable|string',
        ]);

        $form->update($request->all());

        return redirect()->route('forms.settings', $form)
            ->with('success', 'Paramètres mis à jour avec succès !');
    }

    /**
     * Afficher les paramètres du formulaire
     */
    public function settings(Form $form)
    {
        $this->authorize('update', $form);

        return view('forms.settings', compact('form'));
    }

    /**
     * Afficher la page de design du formulaire
     */
    public function design(Form $form)
    {
        $this->authorize('update', $form);

        return view('forms.design', compact('form'));
    }

    /**
     * Mettre à jour le design du formulaire
     */
    public function updateDesign(Request $request, Form $form)
    {
        $this->authorize('update', $form);

        $request->validate([
            'primary_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['primary_color', 'background_color']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('form-logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('form-covers', 'public');
        }

        $form->update($data);

        return redirect()->route('forms.design', $form)
            ->with('success', 'Design mis à jour avec succès !');
    }


}
