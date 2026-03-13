<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Afficher le profil
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($request->only(['name', 'email', 'company_name', 'phone']));

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function passwordForm()
    {
        return view('profile.password');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Mot de passe modifié avec succès !');
    }

    /**
     * Uploader un avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'] // 2MB max
        ]);

        $user = auth()->user();

        // Supprimer l'ancien avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Uploader le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return redirect()->back()
            ->with('success', 'Avatar mis à jour avec succès !');
    }

    /**
     * Supprimer l'avatar
     */
    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->back()
            ->with('success', 'Avatar supprimé avec succès !');
    }

    /**
     * Paramètres de notification
     */
    public function notificationSettings()
    {
        $user = auth()->user();
        $settings = $user->settings['notifications'] ?? [
            'email_responses' => true,
            'email_subscription' => true,
            'email_updates' => false,
        ];

        return view('profile.notifications', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres de notification
     */
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();

        $settings = $user->settings ?? [];
        $settings['notifications'] = $request->only([
            'email_responses',
            'email_subscription',
            'email_updates'
        ]);

        $user->update(['settings' => $settings]);

        return redirect()->back()
            ->with('success', 'Préférences de notification mises à jour !');
    }

    /**
     * Supprimer le compte
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password']
        ]);

        $user = auth()->user();

        // Supprimer l'avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Supprimer le compte
        $user->delete();

        auth()->logout();

        return redirect('/')
            ->with('success', 'Votre compte a été supprimé. Nous espérons vous revoir bientôt !');
    }

    /**
     * Exporter les données personnelles
     */
    public function exportData()
    {
        $user = auth()->user();

        $data = [
            'user' => $user->toArray(),
            'forms' => $user->forms()->with('fields', 'responses')->get()->toArray(),
        ];

        $filename = 'masadigitale-export-' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
