<?php
// app/Notifications/NewTemplateNotification.php

namespace App\Notifications;

use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTemplateNotification extends Notification
{
    use Queueable;

    protected $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '✨ Nouveau template disponible',
            'message' => "Un nouveau template \"{$this->template->name}\" vient d'être ajouté dans la catégorie {$this->template->category}. Créez un formulaire en un clic !",
            'template_id' => $this->template->id,
            'template_name' => $this->template->name,
            'category' => $this->template->category,
            'is_premium' => $this->template->is_premium,
            'type' => 'new_template',
            'action_url' => route('templates.show', $this->template->slug),
            'action_text' => 'Voir le template',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
