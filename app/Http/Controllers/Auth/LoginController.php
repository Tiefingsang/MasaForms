<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Vérifier si l'utilisateur est actif
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été désactivé. Veuillez contacter l\'administrateur.',
                ]);
            }

            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => __('Les identifiants fournis ne correspondent pas à nos enregistrements.'),
        ]);
    }
}
