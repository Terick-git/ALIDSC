<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationMessage;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NouveauMessageContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
{
    // ← CHEMIN MODIFIÉ
    return view('pages.contact');
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'nullable|string|max:50',
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string|min:10|max:5000',
        ]);

        // 1. On enregistre en BDD
        $message = Message::create($validated);

        // 2. On notifie tous les admins par mail
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NouveauMessageContact($message));
        }

        // 3. On envoie une confirmation à l'expéditeur
        try {
            Mail::to($message->email)->send(new ConfirmationMessage($message));
        } catch (\Exception $e) {
            // On log l'erreur mais on ne bloque pas l'utilisateur
            \Log::error('Erreur envoi confirmation : ' . $e->getMessage());
        }

        return redirect()->route('contact')
            ->with('success', 'Merci ! Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
    }
}