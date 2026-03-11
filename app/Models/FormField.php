<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id', 'label', 'name', 'type', 'placeholder', 'help_text',
        'options', 'is_required', 'order', 'default_value', 'validation_rules',
        'conditional_logic', 'file_types', 'max_file_size', 'min_length',
        'max_length', 'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'validation_rules' => 'array',
        'conditional_logic' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($field) {
            $field->name = $field->name ?: Str::slug($field->label) . '_' . Str::random(5);
        });
    }

    // Relations
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function responseValues()
    {
        return $this->hasMany(ResponseValue::class);
    }

    // Méthodes utilitaires
    public function getValidationRules()
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        }

        if ($this->type === 'email') {
            $rules[] = 'email';
        }

        if ($this->min_length) {
            $rules[] = 'min:' . $this->min_length;
        }

        if ($this->max_length) {
            $rules[] = 'max:' . $this->max_length;
        }

        if ($this->validation_rules) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return $rules;
    }
}
