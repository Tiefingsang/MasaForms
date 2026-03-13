<?php
// app/Models/ApiKey.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'key',
        'last_used_at',
        'expires_at',
        'is_active',
        'permissions',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'permissions' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($apiKey) {
            $apiKey->key = Str::random(32);
        });
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si la clé est expirée
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Vérifier si la clé a une permission spécifique
     */
    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Mettre à jour la date de dernière utilisation
     */
    public function markAsUsed()
    {
        $this->update(['last_used_at' => now()]);
    }
}
