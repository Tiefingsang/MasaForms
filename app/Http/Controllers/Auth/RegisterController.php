<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'terms' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
            'phone' => $request->phone,
        ]);

        // Attribuer le plan gratuit
        $freePlan = Plan::where('slug', 'free')->first();
        if ($freePlan) {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $freePlan->id,
                'subscription_type' => 'monthly',
                'starts_at' => now(),
                'status' => 'active',
            ]);
        }

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Bienvenue sur Masadigitale Forms !');
    }
}
