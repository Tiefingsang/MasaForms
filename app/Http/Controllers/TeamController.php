<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInvitationMail;

class TeamController extends Controller
{
    /**
     * Constructeur - Vérifie que l'utilisateur est connecté
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher la liste des membres de l'équipe
     */
    public function index()
    {
        $user = auth()->user();

        // Vérifier si l'utilisateur a accès à la gestion d'équipe
        $plan = $user->currentPlan()->first();
        if (!$plan || !$plan->has_multi_users) {
            return redirect()->route('plans.index')
                ->with('error', 'La gestion d\'équipe est disponible uniquement sur le plan Business.');
        }

        // Récupérer les membres de l'équipe (à adapter selon votre logique)
        $teamMembers = $user->teamMembers ?? collect();

        // Récupérer les invitations en attente
        $pendingInvitations = TeamInvitation::where('inviter_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return view('team.index', compact('teamMembers', 'pendingInvitations'));
    }

    /**
     * Afficher le formulaire d'invitation
     */
    public function create()
    {
        return view('team.create');
    }

    /**
     * Envoyer une invitation à un membre de l'équipe
     */
    public function invite(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'string', 'in:admin,editor,viewer'],
        ]);

        $user = auth()->user();

        // Vérifier les limites du plan
        $plan = $user->currentPlan()->first();
        $currentTeamCount = $user->teamMembers()->count();

        if ($plan && $plan->max_team_members && $currentTeamCount >= $plan->max_team_members) {
            return redirect()->back()
                ->with('error', 'Vous avez atteint la limite de membres pour votre plan.');
        }

        try {
            DB::beginTransaction();

            // Créer l'invitation
            $invitation = TeamInvitation::create([
                'inviter_id' => $user->id,
                'email' => $request->email,
                'role' => $request->role,
                'token' => md5(uniqid() . $request->email . time()),
                'expires_at' => now()->addDays(7),
                'status' => 'pending',
            ]);

            // Envoyer l'email d'invitation
            Mail::to($request->email)->send(new TeamInvitationMail($invitation, $user));

            DB::commit();

            return redirect()->route('team.index')
                ->with('success', 'Invitation envoyée avec succès à ' . $request->email);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'envoi de l\'invitation : ' . $e->getMessage());
        }
    }

    /**
     * Accepter une invitation
     */
    public function acceptInvitation($token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Connectez-vous pour accepter l\'invitation.');
        }

        // Vérifier que l'email correspond
        if (auth()->user()->email !== $invitation->email) {
            return redirect()->route('dashboard')
                ->with('error', 'Cette invitation est destinée à une autre adresse email.');
        }

        try {
            DB::beginTransaction();

            // Ajouter l'utilisateur à l'équipe
            $inviter = User::find($invitation->inviter_id);
            // Logique d'ajout à l'équipe (à adapter selon votre structure)
            // $inviter->teamMembers()->attach(auth()->id(), ['role' => $invitation->role]);

            // Marquer l'invitation comme acceptée
            $invitation->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('team.index')
                ->with('success', 'Vous avez rejoint l\'équipe avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('dashboard')
                ->with('error', 'Erreur lors de l\'acceptation de l\'invitation.');
        }
    }

    /**
     * Refuser une invitation
     */
    public function declineInvitation($token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $invitation->update([
            'status' => 'declined',
            'declined_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('info', 'Invitation refusée.');
    }

    /**
     * Annuler une invitation
     */
    public function cancelInvitation(TeamInvitation $invitation)
    {
        // Vérifier que l'utilisateur est bien l'inviteur
        if ($invitation->inviter_id !== auth()->id()) {
            abort(403);
        }

        $invitation->update(['status' => 'cancelled']);

        return redirect()->route('team.index')
            ->with('success', 'Invitation annulée.');
    }

    /**
     * Retirer un membre de l'équipe
     */
    public function removeMember(User $member)
    {
        $user = auth()->user();

        // Empêcher de se retirer soi-même
        if ($member->id === $user->id) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas vous retirer vous-même.');
        }

        // Logique de retrait (à adapter selon votre structure)
        // $user->teamMembers()->detach($member->id);

        return redirect()->route('team.index')
            ->with('success', 'Membre retiré de l\'équipe.');
    }

    /**
     * Mettre à jour le rôle d'un membre
     */
    public function updateRole(Request $request, User $member)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:admin,editor,viewer'],
        ]);

        $user = auth()->user();

        // Logique de mise à jour du rôle (à adapter selon votre structure)
        // $user->teamMembers()->updateExistingPivot($member->id, ['role' => $request->role]);

        return redirect()->route('team.index')
            ->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Quitter l'équipe
     */
    public function leaveTeam()
    {
        $user = auth()->user();
        $teamOwner = User::find($user->team_owner_id); // À adapter

        if (!$teamOwner) {
            return redirect()->back()
                ->with('error', 'Impossible de quitter l\'équipe.');
        }

        // Logique pour quitter l'équipe (à adapter)
        // $teamOwner->teamMembers()->detach($user->id);

        return redirect()->route('dashboard')
            ->with('success', 'Vous avez quitté l\'équipe.');
    }
}
