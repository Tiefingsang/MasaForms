<?php
// app/Notifications/NewFormResponseNotification.php

namespace App\Notifications;

use App\Models\Form;
use App\Models\FormResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewFormResponseNotification extends Notification
{
    use Queueable;

    protected $form;
    protected $response;

    public function __construct(Form $form, FormResponse $response)
    {
        $this->form = $form;
        $this->response = $response;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouvelle réponse reçue',
            'message' => 'Vous avez reçu une nouvelle réponse pour le formulaire "' . $this->form->title . '"',
            'form_id' => $this->form->id,
            'response_id' => $this->response->id,
            'respondent' => $this->response->respondent_email ?? 'Anonyme',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
