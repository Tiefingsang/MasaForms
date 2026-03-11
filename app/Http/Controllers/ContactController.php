<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Ici, vous pouvez envoyer un email
        // Mail::to('contact@masadigitale.com')->send(new ContactMail($request->all()));

        // Ou simplement enregistrer en base de données
        // Contact::create($request->all());

        return redirect()->back()->with('success', 'Message envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}
