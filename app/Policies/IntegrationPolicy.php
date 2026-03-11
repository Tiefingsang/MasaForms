<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Integration;

class IntegrationPolicy
{
    /**
     * Vérifie si l'utilisateur peut voir l'intégration
     */
    public function view(User $user, Integration $integration): bool
    {
        return $user->id === $integration->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut créer une intégration
     */
    public function create(User $user): bool
    {
        // Vérifier si le plan de l'utilisateur permet les intégrations
        $plan = $user->currentPlan()->first();
        return $plan && $plan->has_whatsapp_integration;
    }

    /**
     * Vérifie si l'utilisateur peut mettre à jour l'intégration
     */
    public function update(User $user, Integration $integration): bool
    {
        return $user->id === $integration->user_id;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer l'intégration
     */
    public function delete(User $user, Integration $integration): bool
    {
        return $user->id === $integration->user_id;
    }
}
