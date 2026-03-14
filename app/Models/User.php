<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'company_name', 'phone',
        'avatar', 'role', 'is_active', 'plan_id' // 👈 AJOUTEZ plan_id ICI
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relations
    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    /**
     * Relation directe avec le plan actuel
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Relation pour obtenir le plan via l'abonnement actif
     */
    public function currentPlan()
    {
        return $this->hasOneThrough(Plan::class, Subscription::class, 'user_id', 'id', 'id', 'plan_id')
                    ->where('subscriptions.status', 'active');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    // Méthodes utilitaires
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function canCreateForm()
    {
        $plan = $this->plan; // Utilisez la relation directe maintenant
        if (!$plan || is_null($plan->max_forms)) {
            return true; // Illimité
        }

        $formsCount = $this->forms()->count();
        return $formsCount < $plan->max_forms;
    }

    public function getFormsLeftAttribute()
    {
        $plan = $this->plan; // Utilisez la relation directe
        if (!$plan || is_null($plan->max_forms)) {
            return '∞';
        }

        $formsCount = $this->forms()->count();
        return max(0, $plan->max_forms - $formsCount);
    }

    public function isUser()
    {
        return $this->hasRole('user');
    }

    public function paymentMethods()
    {
        return $this->payments(); // Relation existante
    }

    
}
