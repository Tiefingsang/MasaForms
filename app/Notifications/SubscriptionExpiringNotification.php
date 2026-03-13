<?php
// app/Notifications/SubscriptionExpiringNotification.php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification
{
    use Queueable;

    protected $subscription;
    protected $daysLeft;

    public function __construct(Subscription $subscription, int $daysLeft)
    {
        $this->subscription = $subscription;
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '⚠️ Abonnement bientôt expiré',
            'message' => "Votre abonnement {$this->subscription->plan->name} expire dans {$this->daysLeft} jours. Pensez à le renouveler pour continuer à profiter de toutes les fonctionnalités.",
            'subscription_id' => $this->subscription->id,
            'plan' => $this->subscription->plan->name,
            'days_left' => $this->daysLeft,
            'expiry_date' => $this->subscription->ends_at->format('d/m/Y'),
            'type' => 'subscription_expiring',
            'action_url' => route('plans.index'),
            'action_text' => 'Renouveler',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
