<?php
// app/Console/Commands/CheckExpiringSubscriptions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Notifications\SubscriptionExpiringNotification;
use App\Notifications\SubscriptionExpiredNotification;

class CheckExpiringSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expiring';
    protected $description = 'Vérifie les abonnements qui expirent bientôt';

    public function handle()
    {
        // Abonnements qui expirent dans 7 jours
        $expiringSoon = Subscription::where('status', 'active')
            ->whereDate('ends_at', now()->addDays(7)->toDateString())
            ->get();

        foreach ($expiringSoon as $subscription) {
            $subscription->user->notify(new SubscriptionExpiringNotification($subscription, 7));
            $this->info("Notification envoyée à {$subscription->user->email} (expire dans 7 jours)");
        }

        // Abonnements qui expirent demain
        $expiringTomorrow = Subscription::where('status', 'active')
            ->whereDate('ends_at', now()->addDay()->toDateString())
            ->get();

        foreach ($expiringTomorrow as $subscription) {
            $subscription->user->notify(new SubscriptionExpiringNotification($subscription, 1));
            $this->info("Notification envoyée à {$subscription->user->email} (expire demain)");
        }

        // Abonnements expirés aujourd'hui
        $expiredToday = Subscription::where('status', 'active')
            ->whereDate('ends_at', now()->toDateString())
            ->get();

        foreach ($expiredToday as $subscription) {
            $subscription->update(['status' => 'expired']);
            $subscription->user->notify(new SubscriptionExpiredNotification($subscription));
            $this->info("Notification envoyée à {$subscription->user->email} (expiré aujourd'hui)");
        }

        $this->info('Vérification des abonnements terminée !');
    }
}
