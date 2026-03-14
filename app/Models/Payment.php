<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'subscription_id', 'transaction_id', 'amount', 'currency',
        'payment_method', 'payment_provider', 'status', 'provider_response', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'provider_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // Méthodes utilitaires
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
