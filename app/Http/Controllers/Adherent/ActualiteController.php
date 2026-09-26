<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use App\Models\Actualite;

class ActualiteController extends Controller
{
    public function index()
    {
        $actualites = Actualite::where('publie', true)
            ->where('interne', true)
            ->latest('publie_le')
            ->paginate(9);

        return view('adherent.actualites.index', compact('actualites'));
    }

    public function show(Actualite $actualite)
    {
        if (!$actualite->publie || !$actualite->interne) {
            abort(404);
        }
        return view('adherent.actualites.show', compact('actualite'));
    }
}