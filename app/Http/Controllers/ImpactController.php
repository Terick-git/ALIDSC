<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Adherent;
use App\Models\Temoignage;

class ImpactController extends Controller
{
    public function index()
{
    $temoignages = \App\Models\Temoignage::where('actif', true)
        ->orderBy('ordre')
        ->get();

    // ← CHEMIN MODIFIÉ
    return view('pages.impact', compact('temoignages'));
}
}