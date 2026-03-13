<?php
// app/Notifications/InactiveFormNotification.php

namespace App\Notifications;

use App\Models\Form;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InactiveFormNotification extends Notification
{
    use Queueable;

    protected $form;
    protected $daysInactive;

    public function __construct(Form $form, int $daysInactive)
    {
        $this->form = $form;
        $this->daysInactive = $daysInactive;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '💤 Formulaire inactif',
            'message' => "Votre formulaire \"{$this->form->title}\" n'a reçu aucune réponse depuis {$this->daysInactive} jours. Pensez à le partager à nouveau !",
            'form_id' => $this->form->id,
            'form_title' => $this->form->title,
            'days_inactive' => $this->daysInactive,
            'last_response' => $this->form->responses()->latest()->first()?->created_at?->format('d/m/Y') ?? 'Jamais',
            'type' => 'inactive_form',
            'action_url' => route('forms.edit', $this->form),
            'action_text' => 'Relancer le formulaire',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
