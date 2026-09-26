<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actualite;
use App\Models\Adherent;
use App\Models\Document;
use App\Models\Message;
use App\Models\Partenaire;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'actualites' => [
                'total' => Actualite::count(),
                'publiees' => Actualite::where('publie', true)->where('interne', false)->count(),
                'internes' => Actualite::where('interne', true)->count(),
            ],
            'actions' => [
                'total' => Action::count(),
                'actives' => Action::where('actif', true)->count(),
            ],
            'partenaires' => [
                'total' => Partenaire::count(),
                'actifs' => Partenaire::where('actif', true)->count(),
            ],
            'messages' => [
                'total' => Message::count(),
                'non_lus' => Message::where('lu', false)->count(),
            ],
            'documents' => [
                'total' => Document::count(),
                'actifs' => Document::where('actif', true)->count(),
            ],
            'adherents' => [
                'total' => Adherent::count(),
                'a_jour' => Adherent::where('statut_demande', 'active')->count(),
                'en_attente' => Adherent::where('statut_demande', 'en_attente')->count(),
                'expires' => Adherent::where('statut_demande', 'expiree')->count(),
                'recettes' => Adherent::where('statut_demande', 'active')->sum('cotisation_montant'),
            ],
        ];

        $derniers_messages = Message::latest()->take(5)->get();
        $derniers_adherents = Adherent::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'derniers_messages', 'derniers_adherents'));
    }
}