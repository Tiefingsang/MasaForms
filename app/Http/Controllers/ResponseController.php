<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormResponsesExport;

class ResponseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher les réponses d'un formulaire
     */
    public function index(Form $form)
    {
        $this->authorize('view', $form);

        $responses = $form->responses()
            ->with('values.field')
            ->latest()
            ->paginate(20);

        return view('responses.index', compact('form', 'responses'));
    }

    /**
     * Afficher une réponse spécifique
     */
    public function show(Form $form, FormResponse $response)
    {
        $this->authorize('view', $form);

        if ($response->form_id !== $form->id) {
            abort(404);
        }

        $response->load('values.field');

        return view('responses.show', compact('form', 'response'));
    }

    /**
     * Supprimer une réponse
     */
    public function destroy(Form $form, FormResponse $response)
    {
        $this->authorize('view', $form);

        if ($response->form_id !== $form->id) {
            abort(404);
        }

        $response->delete();

        // Décrémenter le compteur
        $form->decrement('current_responses');

        return redirect()->route('forms.responses', $form)
            ->with('success', 'Réponse supprimée avec succès');
    }

    /**
     * Exporter les réponses en CSV
     */

    public function exportCsv(Form $form)
    {
        $this->authorize('view', $form);

        return Excel::download(
            new FormResponsesExport($form),
            'reponses-' . $form->slug . '-' . now()->format('Y-m-d') . '.csv',
            \Maatwebsite\Excel\Excel::CSV
        );
    }

    /**
     * Exporter les réponses en Excel
     */
    public function exportExcel(Form $form)
    {
        $this->authorize('view', $form);

        // Vérifier si l'utilisateur a accès à l'export Excel
        $plan = auth()->user()->currentPlan()->first();
        if (!$plan || !$plan->has_export_excel) {
            return redirect()->back()
                ->with('error', 'L\'export Excel n\'est pas disponible dans votre plan.');
        }

        return Excel::download(
            new FormResponsesExport($form),
            'reponses-' . $form->slug . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Exporter les réponses en PDF
     */
    public function exportPdf(Form $form)
    {
        $this->authorize('view', $form);

        // Vérifier si l'utilisateur a accès à l'export PDF
        $plan = auth()->user()->currentPlan()->first();
        if (!$plan || !$plan->has_export_pdf) {
            return redirect()->back()
                ->with('error', 'L\'export PDF n\'est pas disponible dans votre plan.');
        }

        $responses = $form->responses()
            ->with('values.field')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('exports.form-responses-pdf', [
            'form' => $form,
            'responses' => $responses
        ]);

        return $pdf->download('reponses-' . $form->slug . '-' . now()->format('Y-m-d') . '.pdf');
    }


    
}
