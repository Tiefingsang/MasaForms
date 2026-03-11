<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id', 'respondent_name', 'respondent_email', 'ip_address',
        'user_agent', 'location_data', 'is_completed', 'completion_time'
    ];

    protected $casts = [
        'location_data' => 'array',
        'is_completed' => 'boolean',
        'completion_time' => 'integer',
    ];

    // Relations
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function values()
    {
        return $this->hasMany(ResponseValue::class);
    }

    // Méthodes utilitaires
    public function getValueForField($fieldName)
    {
        $field = FormField::where('form_id', $this->form_id)
                         ->where('name', $fieldName)
                         ->first();

        if (!$field) {
            return null;
        }

        $value = $this->values()->where('form_field_id', $field->id)->first();
        return $value ? $value->value : null;
    }

    public function getAllValuesAsArray()
    {
        $values = [];
        foreach ($this->values as $value) {
            $values[$value->field->name] = $value->value;
        }
        return $values;
    }
}
