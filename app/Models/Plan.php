<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'max_forms',
        'max_responses_per_form',
        'has_advanced_stats',
        'has_export_csv',
        'has_export_excel',
        'has_export_pdf',
        'has_multi_users',
        'has_automations',
        'has_custom_logo',
        'has_remove_masadigitale_logo',
        'has_whatsapp_integration',
        'has_email_notifications',
        'has_api_access',
        'has_templates',
        'price_monthly',
        'price_yearly',
        'price_monthly_usd',      // 👈 NOUVEAU : Prix en USD pour Stripe/PayPal
        'price_yearly_usd',       // 👈 NOUVEAU : Prix annuel en USD
        'price_monthly_eur',      // 👈 NOUVEAU : Prix en EUR
        'price_yearly_eur',       // 👈 NOUVEAU : Prix annuel en EUR
        'stripe_price_id_monthly', // 👈 NOUVEAU : ID du prix Stripe (mensuel)
        'stripe_price_id_yearly',  // 👈 NOUVEAU : ID du prix Stripe (annuel)
        'paypal_plan_id',          // 👈 NOUVEAU : ID du plan PayPal
        'currency',                // 👈 NOUVEAU : Devise par défaut (XOF, USD, EUR)
        'sort_order',
        'is_popular',
        'is_active',
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
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'price_monthly_usd' => 'decimal:2',
        'price_yearly_usd' => 'decimal:2',
        'price_monthly_eur' => 'decimal:2',
        'price_yearly_eur' => 'decimal:2',
    ];

    // Relations existantes
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    // 👇 NOUVELLES MÉTHODES

    /**
     * Obtenir le prix formaté selon la devise
     */
    public function getFormattedPrice($interval = 'monthly', $currency = null)
    {
        $currency = $currency ?? $this->currency ?? 'XOF';

        $price = $interval === 'monthly'
            ? $this->getPriceForCurrency('monthly', $currency)
            : $this->getPriceForCurrency('yearly', $currency);

        return $this->formatPrice($price, $currency);
    }

    /**
     * Obtenir le prix pour une devise spécifique
     */
    public function getPriceForCurrency($interval = 'monthly', $currency = 'XOF')
    {
        $field = $interval === 'monthly' ? 'price_monthly' : 'price_yearly';

        switch (strtoupper($currency)) {
            case 'USD':
                $field .= '_usd';
                break;
            case 'EUR':
                $field .= '_eur';
                break;
            default:
                // Garder le champ original (XOF)
                break;
        }

        return $this->$field ?? 0;
    }

    /**
     * Formater le prix selon la devise
     */
    private function formatPrice($price, $currency)
    {
        switch (strtoupper($currency)) {
            case 'USD':
                return '$' . number_format($price, 2);
            case 'EUR':
                return '€' . number_format($price, 2);
            default:
                return number_format($price, 0, ',', ' ') . ' FCFA';
        }
    }

    /**
     * Obtenir la description pour le paiement
     */
    public function getPaymentDescription($interval = 'monthly')
    {
        $intervalText = $interval === 'monthly' ? 'mensuel' : 'annuel';
        return "Abonnement {$this->name} - {$intervalText}";
    }

    /**
     * Créer ou mettre à jour les prix sur Stripe
     */
    public function syncWithStripe()
    {
        if (!config('services.stripe.enabled')) {
            return;
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Créer/Mettre à jour le produit Stripe
            $product = \Stripe\Product::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            // Créer le prix mensuel en USD
            if ($this->price_monthly_usd) {
                $price = \Stripe\Price::create([
                    'product' => $product->id,
                    'unit_amount' => $this->price_monthly_usd * 100, // Stripe utilise les centimes
                    'currency' => 'usd',
                    'recurring' => ['interval' => 'month'],
                ]);
                $this->stripe_price_id_monthly = $price->id;
            }

            // Créer le prix annuel en USD
            if ($this->price_yearly_usd) {
                $price = \Stripe\Price::create([
                    'product' => $product->id,
                    'unit_amount' => $this->price_yearly_usd * 100,
                    'currency' => 'usd',
                    'recurring' => ['interval' => 'year'],
                ]);
                $this->stripe_price_id_yearly = $price->id;
            }

            $this->save();

        } catch (\Exception $e) {
            \Log::error('Erreur synchronisation Stripe: ' . $e->getMessage());
        }
    }

    // Vos méthodes existantes formatées
    public function getPriceMonthlyFormattedAttribute()
    {
        return $this->formatPrice($this->price_monthly, 'XOF');
    }

    public function getPriceYearlyFormattedAttribute()
    {
        return $this->formatPrice($this->price_yearly, 'XOF');
    }

    public function getPriceMonthlyUsdFormattedAttribute()
    {
        return $this->formatPrice($this->price_monthly_usd, 'USD');
    }

    public function getPriceYearlyUsdFormattedAttribute()
    {
        return $this->formatPrice($this->price_yearly_usd, 'USD');
    }



}
