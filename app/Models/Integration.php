<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'type', 'credentials', 'settings', 'is_active'
    ];

    protected $casts = [
        'credentials' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'credentials', // Cacher les informations sensibles
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes utilitaires
    public function isConnected()
    {
        return $this->is_active && !empty($this->credentials);
    }

    public function getProvider()
    {
        return $this->type;
    }
}
