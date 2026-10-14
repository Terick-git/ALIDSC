<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Models\Document;
use App\Models\Evenement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $adherent = Auth::guard('adherent')->user();

        // Derniers documents publiés (3)
        $documents = Document::where('actif', true)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Prochains événements (2)
        $evenements = Evenement::where('statut', 'publie')
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut')
            ->take(2)
            ->get();

        // Dernières actualités internes (3)
        $actualites = Actualite::where('publie', true)
            ->where('interne', true)
            ->latest('publie_le')
            ->take(3)
            ->get();

        // État de la cotisation
        $cotisation = [
            'statut'           => $adherent->statut_cotisation,
            'label'            => match ($adherent->statut_cotisation) {
                'a_jour'     => 'À jour',
                'en_attente' => 'En attente',
                'expire'     => 'Expirée',
                default      => $adherent->statut_cotisation,
            },
            'montant'          => $adherent->cotisation_montant,
            'dernier_paiement' => $adherent->date_dernier_paiement,
            'jours_restants'   => $adherent->jours_avant_expiration,
        ];

        return view('adherent.dashboard', compact(
            'adherent',
            'documents',
            'evenements',
            'actualites',
            'cotisation'
        ));
    }
}