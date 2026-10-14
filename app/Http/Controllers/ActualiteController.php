<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Actualite;
use App\Models\Partenaire;
use App\Models\Temoignage;

class ActualiteController extends Controller
{
    public function home()
    {
        $actualites = Actualite::where('publie', true)
            ->where('interne', false)
            ->latest('publie_le')
            ->take(3)
            ->get();

        $temoignages = Temoignage::where('actif', true)
            ->orderBy('ordre')
            ->take(3)
            ->get();

        // Pour les sections à venir
        $actions = Action::where('actif', true)
            ->orderBy('ordre')
            ->take(3)
            ->get();

        $partenaires = Partenaire::where('actif', true)
            ->orderBy('ordre')
            ->take(8)
            ->get();

        return view('pages.home', compact(
            'actualites',
            'temoignages',
            'actions',
            'partenaires'
        ));
    }

    public function index()
    {
        $actualites = Actualite::where('publie', true)
            ->where('interne', false)
            ->latest('publie_le')
            ->paginate(9);

        return view('pages.actualites', compact('actualites'));
    }

    public function show($slug)
    {
        $actualite = Actualite::where('slug', $slug)
            ->where('publie', true)
            ->where('interne', false)
            ->firstOrFail();

        $similaires = Actualite::where('publie', true)
            ->where('interne', false)
            ->where('id', '!=', $actualite->id)
            ->latest('publie_le')
            ->take(3)
            ->get();

        return view('pages.actualite', compact('actualite', 'similaires'));
    }
}