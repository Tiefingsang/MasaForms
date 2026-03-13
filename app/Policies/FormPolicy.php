<?php

namespace App\Policies;

use App\Models\Form;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FormPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tout utilisateur connecté peut voir la liste de ses formulaires
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Form $form): bool
    {
        // L'utilisateur peut voir le formulaire s'il en est le propriétaire
        return $user->id === $form->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Tout utilisateur connecté peut créer un formulaire
        // (vous pouvez ajouter des vérifications de limite de plan ici)
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Form $form): bool
    {
        // L'utilisateur peut modifier le formulaire s'il en est le propriétaire
        return $user->id === $form->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Form $form): bool
    {
        // L'utilisateur peut supprimer le formulaire s'il en est le propriétaire
        return $user->id === $form->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Form $form): bool
    {
        // Seul le propriétaire peut restaurer (si vous utilisez soft deletes)
        return $user->id === $form->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Form $form): bool
    {
        // Seul le propriétaire peut supprimer définitivement
        return $user->id === $form->user_id;
    }

    /**
     * Optional: Add a before method for admin override
     */
    public function before(User $user, string $ability): bool|null
    {
        // Si l'utilisateur est admin, il peut tout faire
        if ($user->isAdmin()) {
            return true;
        }

        return null; // Continue vers la méthode spécifique
    }
}
