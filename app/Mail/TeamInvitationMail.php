<?php
// app/Mail/TeamInvitationMail.php

namespace App\Mail;

use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $inviter;

    public function __construct(TeamInvitation $invitation, User $inviter)
    {
        $this->invitation = $invitation;
        $this->inviter = $inviter;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation à rejoindre une équipe sur Masadigitale Forms',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
        );
    }
}
