<?php
// app/Notifications/FormLimitNotification.php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FormLimitNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $currentCount;
    protected $maxForms;

    public function __construct(User $user, int $currentCount, int $maxForms)
    {
        $this->user = $user;
        $this->currentCount = $currentCount;
        $this->maxForms = $maxForms;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $percentage = round(($this->currentCount / $this->maxForms) * 100);

        return [
            'title' => '📊 Limite de formulaires approchée',
            'message' => "Vous avez utilisé {$this->currentCount} formulaires sur {$this->maxForms} ({$percentage}%). Passez à un plan supérieur pour créer plus de formulaires.",
            'current_count' => $this->currentCount,
            'max_forms' => $this->maxForms,
            'percentage' => $percentage,
            'type' => 'form_limit',
            'action_url' => route('plans.index'),
            'action_text' => 'Augmenter ma limite',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
