<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_id', 'subscription_type', 'starts_at', 'ends_at',
        'trial_ends_at', 'cancelled_at', 'status', 'meta_data'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'meta_data' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Méthodes utilitaires
    public function isActive()
    {
        return $this->status === 'active' &&
               ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function isOnTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
