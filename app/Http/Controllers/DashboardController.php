<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actualite;
use App\Models\Adherent;
use App\Models\Document;
use App\Models\Donation;
use App\Models\Message;
use App\Models\Partenaire;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'actualites' => [
                'total'    => Actualite::count(),
                'publiees' => Actualite::where('publie', true)->where('interne', false)->count(),
                'internes' => Actualite::where('interne', true)->count(),
            ],
            'actions' => [
                'total'   => Action::count(),
                'actives' => Action::where('actif', true)->count(),
            ],
            'partenaires' => [
                'total'  => Partenaire::count(),
                'actifs' => Partenaire::where('actif', true)->count(),
            ],
            'messages' => [
                'total'    => Message::count(),
                'non_lus'  => Message::where('lu', false)->count(),
            ],
            'documents' => [
                'total'  => Document::count(),
                'actifs' => Document::where('actif', true)->count(),
            ],
            'adherents' => [
                'total'      => Adherent::count(),
                'a_jour'     => Adherent::where('statut_demande', 'active')->count(),
                'en_attente' => Adherent::where('statut_demande', 'en_attente')->count(),
                'expires'    => Adherent::where('statut_demande', 'expiree')->count(),
                'recettes'   => Adherent::where('statut_demande', 'active')->sum('cotisation_montant'),
            ],
            'dons' => [
                'total'         => Donation::count(),
                'payes'         => Donation::where('statut', 'paye')->count(),
                'en_attente'    => Donation::where('statut', 'en_attente')->count(),
                'montant_total' => Donation::where('statut', 'paye')->sum('montant'),
                'montant_mois'  => Donation::where('statut', 'paye')
                    ->where('paid_at', '>=', now()->startOfMonth())
                    ->sum('montant'),
                'count_mois'    => Donation::where('statut', 'paye')
                    ->where('paid_at', '>=', now()->startOfMonth())
                    ->count(),
            ],
        ];

        $derniers_messages   = Message::latest()->take(5)->get();
        $derniers_adherents  = Adherent::latest()->take(5)->get();
        $derniers_dons       = Donation::latest()->take(5)->get();

        return view('dashboard', compact(
            'stats',
            'derniers_messages',
            'derniers_adherents',
            'derniers_dons'
        ));
    }
}