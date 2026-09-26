<?php

namespace App\Http\Controllers;

use App\Models\Evenement;

class EvenementController extends Controller
{
    /**
     * Liste des événements (à venir + passés).
     */
    public function index()
    {
        $evenementsAVenir = Evenement::where('statut', 'publie')
            ->where('date_debut', '>=', now())
            ->orderBy('date_debut')
            ->get();

        $evenementsPasses = Evenement::where('statut', 'publie')
            ->where('date_debut', '<', now())
            ->orderByDesc('date_debut')
            ->take(12)
            ->get();

        return view('pages.evenements', compact('evenementsAVenir', 'evenementsPasses'));
    }

    /**
     * Détail d'un événement publié.
     */
    public function show($slug)
    {
        $evenement = Evenement::where('slug', $slug)
            ->where('statut', 'publie')
            ->firstOrFail();

        return view('pages.evenement', compact('evenement'));
    }
}