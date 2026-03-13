<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'thank_you_message',
        'logo_path', 'cover_image', 'primary_color', 'background_color',
        'is_public', 'is_active', 'accepts_responses', 'show_progress_bar',
        'captcha_enabled', 'max_responses', 'current_responses', 'start_date',
        'end_date', 'password', 'settings'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'accepts_responses' => 'boolean',
        'show_progress_bar' => 'boolean',
        'captcha_enabled' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($form) {
            $form->slug = $form->slug ?: Str::random(10);
        });
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fields()
    {
        return $this->hasMany(FormField::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }

    // Méthodes utilitaires
    public function getPublicUrlAttribute()
    {
        return route('forms.public.show', ['slug' => $this->slug]);
    }

    public function isAcceptingResponses()
    {
        if (!$this->accepts_responses || !$this->is_active) {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        if ($this->max_responses && $this->current_responses >= $this->max_responses) {
            return false;
        }

        return true;
    }

    public function incrementResponsesCount()
    {
        $this->increment('current_responses');
    }

    public function hasReachedLimit()
    {
        return $this->max_responses && $this->current_responses >= $this->max_responses;
    }
}
