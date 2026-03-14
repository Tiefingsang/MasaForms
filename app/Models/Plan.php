<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'max_forms', 'max_responses_per_form',
        'has_advanced_stats', 'has_export_csv', 'has_export_excel', 'has_export_pdf',
        'has_multi_users', 'has_automations', 'has_custom_logo', 'has_remove_masadigitale_logo',
        'has_whatsapp_integration', 'has_email_notifications', 'has_api_access', 'has_templates',
        'price_monthly', 'price_yearly', 'sort_order', 'is_popular', 'is_active', 
    ];

    protected $casts = [
        'has_advanced_stats' => 'boolean',
        'has_export_csv' => 'boolean',
        'has_export_excel' => 'boolean',
        'has_export_pdf' => 'boolean',
        'has_multi_users' => 'boolean',
        'has_automations' => 'boolean',
        'has_custom_logo' => 'boolean',
        'has_remove_masadigitale_logo' => 'boolean',
        'has_whatsapp_integration' => 'boolean',
        'has_email_notifications' => 'boolean',
        'has_api_access' => 'boolean',
        'has_templates' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relations
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    // Méthodes utilitaires
    public function getPriceMonthlyFormattedAttribute()
    {
        return number_format($this->price_monthly, 0, ',', ' ') . ' FCFA';
    }

    public function getPriceYearlyFormattedAttribute()
    {
        return number_format($this->price_yearly, 0, ',', ' ') . ' FCFA';
    }
}
