<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher les statistiques globales
     */
    public function global()
    {
        $user = auth()->user();

        $stats = [
            'total_forms' => $user->forms()->count(),
            'total_responses' => FormResponse::whereIn('form_id', $user->forms()->pluck('id'))->count(),
            'active_forms' => $user->forms()->where('is_active', true)->count(),
            'avg_responses_per_form' => $user->forms()->withCount('responses')->get()->avg('responses_count') ?? 0,
        ];

        // Statistiques par jour pour le graphique
        $responsesByDay = FormResponse::whereIn('form_id', $user->forms()->pluck('id'))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        // Top formulaires
        $topForms = $user->forms()
            ->withCount('responses')
            ->orderBy('responses_count', 'desc')
            ->limit(5)
            ->get();

        return view('statistics.global', compact('stats', 'responsesByDay', 'topForms'));
    }

    /**
     * Statistiques pour un formulaire spécifique
     */
    public function formStats(Form $form)
{   //dd('jjjjjj');
    $this->authorize('view', $form);

    $stats = [
        'total_responses' => $form->responses()->count(),
        'completion_rate' => $this->calculateCompletionRate($form),
        'avg_completion_time' => $form->responses()->avg('completion_time'),
        'responses_by_day' => $form->responses()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get(),
        'responses_by_hour' => $form->responses()
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get(),
    ];

    // Statistiques par champ
    $fieldStats = [];
    foreach ($form->fields as $field) {
        $fieldStats[$field->id] = [
            'field' => $field,
            'total' => $field->responseValues()->count(),
            'filled' => $field->responseValues()->whereNotNull('value')->count(),
            'empty' => $field->responseValues()->whereNull('value')->count(),
        ];

        // Pour les champs à choix multiples, ajouter la distribution
        if (in_array($field->type, ['radio', 'select', 'checkbox'])) {
            $fieldStats[$field->id]['distribution'] = $field->responseValues()
                ->select('value', DB::raw('count(*) as total'))
                ->groupBy('value')
                ->orderBy('total', 'desc')
                ->get();
        }
    }

    return view('statistics.form', compact('form', 'stats', 'fieldStats'));
}



    /**
     * Exporter les statistiques
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'csv');

        // Logique d'export à implémenter
        // ...

        return redirect()->back()->with('success', 'Export en cours de préparation...');
    }

    /**
     * Calculer le taux de complétion d'un formulaire
     */
    private function calculateCompletionRate(Form $form)
    {
        $totalResponses = $form->responses()->count();
        if ($totalResponses === 0) {
            return 0;
        }

        $completedResponses = $form->responses()->where('is_completed', true)->count();
        return round(($completedResponses / $totalResponses) * 100, 2);
    }

    /**
     * Obtenir la distribution des valeurs pour un champ
     */
    private function getFieldValueDistribution($field)
    {
        if (in_array($field->type, ['radio', 'select', 'checkbox'])) {
            $values = $field->responseValues()
                ->select('value', DB::raw('count(*) as total'))
                ->groupBy('value')
                ->orderBy('total', 'desc')
                ->get();

            $total = $values->sum('total');

            return $values->map(function ($item) use ($total) {
                return [
                    'value' => $item->value,
                    'count' => $item->total,
                    'percentage' => $total > 0 ? round(($item->total / $total) * 100, 2) : 0,
                ];
            });
        }

        return [];
    }
}
