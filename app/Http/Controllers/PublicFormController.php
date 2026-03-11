<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use App\Models\ResponseValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicFormController extends Controller
{
    /**
     * Afficher le formulaire public
     */
    public function show($slug)
    {
        $form = Form::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_public', true)
            ->firstOrFail();

        if (!$form->isAcceptingResponses()) {
            return view('forms.closed', compact('form'));
        }

        return view('forms.public', compact('form'));
    }

    /**
     * Traiter la soumission du formulaire
     */
    public function submit(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)
            ->where('is_active', true)
            ->where('is_public', true)
            ->firstOrFail();

        if (!$form->isAcceptingResponses()) {
            return response()->json([
                'error' => 'Ce formulaire n\'accepte plus de réponses'
            ], 403);
        }

        // Vérifier les limites du plan
        $plan = $form->user->currentPlan()->first();
        if ($plan && $plan->max_responses_per_form) {
            if ($form->current_responses >= $plan->max_responses_per_form) {
                return response()->json([
                    'error' => 'Ce formulaire a atteint sa limite de réponses'
                ], 403);
            }
        }

        // Valider les données
        $validationRules = [];
        foreach ($form->fields as $field) {
            $rules = $field->getValidationRules();
            if (!empty($rules)) {
                $validationRules['field_' . $field->id] = implode('|', $rules);
            }
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Enregistrer la réponse
        $response = FormResponse::create([
            'form_id' => $form->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'respondent_name' => $request->input('respondent_name'),
            'respondent_email' => $request->input('respondent_email'),
        ]);

        // Enregistrer les valeurs
        foreach ($form->fields as $field) {
            $value = $request->input('field_' . $field->id);

            if ($value !== null) {
                ResponseValue::create([
                    'form_response_id' => $response->id,
                    'form_field_id' => $field->id,
                    'value' => is_array($value) ? json_encode($value) : $value,
                ]);
            }
        }

        // Incrémenter le compteur
        $form->incrementResponsesCount();

        // Envoyer des notifications si configuré
        // $this->sendNotifications($form, $response);

        return response()->json([
            'success' => true,
            'message' => $form->thank_you_message ?? 'Merci pour votre réponse !',
            'redirect' => $form->settings['redirect_url'] ?? null
        ]);
    }
}
