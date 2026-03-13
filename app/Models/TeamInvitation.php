<?php
// app/Models/TeamInvitation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inviter_id',
        'email',
        'role',
        'token',
        'expires_at',
        'accepted_at',
        'declined_at',
        'status',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    /**
     * Relation avec l'inviteur
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    /**
     * Vérifier si l'invitation est expirée
     */
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    /**
     * Vérifier si l'invitation est en attente
     */
    public function isPending()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
