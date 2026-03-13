<?php

namespace App\Exports;

use App\Models\Form;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormResponsesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->form->responses()
            ->with('values.field')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        $headings = ['Date de soumission', 'IP', 'Email du répondant', 'Nom du répondant'];

        // Ajouter les titres des champs
        foreach ($this->form->fields as $field) {
            $headings[] = $field->label;
        }

        return $headings;
    }

    public function map($response): array
    {
        $data = [
            $response->created_at->format('d/m/Y H:i'),
            $response->ip_address ?? 'N/A',
            $response->respondent_email ?? 'N/A',
            $response->respondent_name ?? 'Anonyme',
        ];

        // Ajouter les valeurs des champs
        $valuesById = $response->values->keyBy('form_field_id');

        foreach ($this->form->fields as $field) {
            $value = $valuesById->get($field->id);
            $data[] = $this->formatValue($value ? $value->value : null, $field->type);
        }

        return $data;
    }

    private function formatValue($value, $type)
    {
        if ($value === null || $value === '') {
            return '-';
        }

        // Décoder le JSON si nécessaire (pour les checkboxes)
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return implode(', ', $decoded);
        }

        // Formater les dates si nécessaire
        if ($type === 'date' && strtotime($value)) {
            return date('d/m/Y', strtotime($value));
        }

        return $value;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style pour la première ligne (en-têtes)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
