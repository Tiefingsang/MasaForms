<?php
// app/Notifications/SubscriptionActivatedNotification.php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionActivatedNotification extends Notification
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
            'title' => '🎉 Abonnement activé',
            'message' => 'Votre abonnement ' . $this->subscription->plan->name . ' a été activé avec succès. Profitez de toutes les fonctionnalités !',
            'subscription_id' => $this->subscription->id,
            'plan' => $this->subscription->plan->name,
            'type' => 'subscription_activated',
            'action_url' => route('plans.index'),
            'action_text' => 'Gérer mon abonnement',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
