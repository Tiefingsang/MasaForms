<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use App\Models\ResponseValue;
use App\Notifications\NewFormResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PublicFormController extends Controller
{
    public function show($slug)
    {
        try {
            Log::info('Affichage formulaire public', ['slug' => $slug]);

            $form = Form::where('slug', $slug)
                ->where('is_active', true)
                ->where('is_public', true)
                ->firstOrFail();

            Log::info('Formulaire trouvé', ['id' => $form->id, 'title' => $form->title]);

            if (!$form->isAcceptingResponses()) {
                Log::warning('Formulaire n\'accepte pas les réponses', ['id' => $form->id]);
                return view('forms.closed', compact('form'));
            }

            return view('forms.public', compact('form'));

        } catch (\Exception $e) {
            Log::error('Erreur affichage formulaire: ' . $e->getMessage(), [
                'slug' => $slug,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404);
        }
    }

    public function submit(Request $request, $slug)
    {
        // Log de la requête reçue
        Log::info('=== SOUMISSION FORMULAIRE REÇUE ===');
        Log::info('Slug: ' . $slug);
        Log::info('Méthode: ' . $request->method());
        Log::info('URL: ' . $request->fullUrl());
        Log::info('Headers: ' . json_encode($request->headers->all()));
        Log::info('Contenu: ' . $request->getContent());
        Log::info('Données: ' . json_encode($request->all()));

        try {
            // Vérifier que le formulaire existe
            $form = Form::where('slug', $slug)
                ->where('is_active', true)
                ->where('is_public', true)
                ->first();

            if (!$form) {
                Log::error('Formulaire non trouvé', ['slug' => $slug]);
                return response()->json([
                    'success' => false,
                    'error' => 'Formulaire non trouvé'
                ], 404);
            }

            Log::info('Formulaire trouvé', [
                'id' => $form->id,
                'title' => $form->title,
                'user_id' => $form->user_id
            ]);

            if (!$form->isAcceptingResponses()) {
                Log::warning('Formulaire n\'accepte pas les réponses');
                return response()->json([
                    'success' => false,
                    'error' => 'Ce formulaire n\'accepte plus de réponses'
                ], 403);
            }

            // Valider les données
            $validationRules = [];
            foreach ($form->fields as $field) {
                $rules = [];

                if ($field->is_required) {
                    $rules[] = 'required';
                }

                switch ($field->type) {
                    case 'email':
                        $rules[] = 'email';
                        break;
                    case 'tel':
                        $rules[] = 'regex:/^[0-9\+\-\s]+$/';
                        break;
                    case 'number':
                        $rules[] = 'numeric';
                        break;
                    case 'date':
                        $rules[] = 'date';
                        break;
                }

                if (!empty($rules)) {
                    $validationRules['field_' . $field->id] = implode('|', $rules);
                }
            }

            Log::info('Règles de validation', $validationRules);

            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                Log::warning('Validation échouée', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Enregistrer la réponse
            $response = FormResponse::create([
                'form_id' => $form->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'respondent_name' => $request->input('respondent_name'),
                'respondent_email' => $request->input('respondent_email'),
            ]);

            Log::info('Réponse créée', ['response_id' => $response->id]);

            // Enregistrer les valeurs
            foreach ($form->fields as $field) {
                $value = $request->input('field_' . $field->id);

                if ($value !== null && $value !== '') {
                    ResponseValue::create([
                        'form_response_id' => $response->id,
                        'form_field_id' => $field->id,
                        'value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value,
                    ]);

                    Log::info('Valeur enregistrée', [
                        'field_id' => $field->id,
                        'field_label' => $field->label,
                        'value' => $value
                    ]);
                }
            }

            // Incrémenter le compteur
            $form->increment('current_responses');
            Log::info('Compteur incrémenté', ['new_count' => $form->current_responses]);

            Log::info('✅ Soumission réussie');

            return response()->json([
                'success' => true,
                'message' => $form->thank_you_message ?? 'Merci pour votre réponse !',
                'redirect' => $form->settings['redirect_url'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('❌ ERREUR CRITIQUE: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}
