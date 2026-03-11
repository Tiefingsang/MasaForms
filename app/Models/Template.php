<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'category', 'thumbnail',
        'structure', 'is_premium', 'usage_count', 'is_active'
    ];

    protected $casts = [
        'structure' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    // Méthodes utilitaires
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function applyToForm(Form $form)
    {
        // Appliquer la structure du template au formulaire
        $fields = $this->structure['fields'] ?? [];

        foreach ($fields as $index => $fieldData) {
            $field = new FormField([
                'label' => $fieldData['label'],
                'type' => $fieldData['type'],
                'placeholder' => $fieldData['placeholder'] ?? null,
                'is_required' => $fieldData['is_required'] ?? false,
                'options' => $fieldData['options'] ?? null,
                'order' => $index,
            ]);

            $form->fields()->save($field);
        }

        $this->incrementUsage();
    }
}
