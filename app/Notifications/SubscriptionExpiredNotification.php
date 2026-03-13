<?php
// app/Notifications/SubscriptionExpiredNotification.php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionExpiredNotification extends Notification
{
    use Queueable;

    protected $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '❌ Abonnement expiré',
            'message' => "Votre abonnement {$this->subscription->plan->name} a expiré. Vous êtes maintenant sur le plan gratuit. Réactivez votre abonnement pour retrouver toutes vos fonctionnalités.",
            'subscription_id' => $this->subscription->id,
            'plan' => $this->subscription->plan->name,
            'expired_at' => $this->subscription->ends_at->format('d/m/Y'),
            'type' => 'subscription_expired',
            'action_url' => route('plans.index'),
            'action_text' => 'Voir les offres',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
