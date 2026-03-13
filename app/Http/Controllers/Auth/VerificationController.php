<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * Afficher la notice de vérification
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Vérifier l'email de l'utilisateur
     */
    public function verify(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('success', 'Votre email est déjà vérifié.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('dashboard')->with('success', 'Votre email a été vérifié avec succès !');
    }

    /**
     * Renvoyer le lien de vérification
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('success', 'Votre email est déjà vérifié.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Un nouveau lien de vérification vous a été envoyé par email.');
    }
}
