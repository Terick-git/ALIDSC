<?php

namespace App\Http\Controllers;

use App\Models\Actualite;

class ActualiteController extends Controller
{
    public function home()
    {
        $actualites = Actualite::where('publie', true)
            ->where('interne', false)
            ->latest('publie_le')
            ->take(3)
            ->get();

        $temoignages = \App\Models\Temoignage::where('actif', true)
            ->orderBy('ordre')
            ->take(1)
            ->get();

        return view('pages.home', compact('actualites', 'temoignages'));
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

        return view('pages.actualite', compact('actualite'));
}
}
