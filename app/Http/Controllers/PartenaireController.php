<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaire::where('actif', true)
            ->orderBy('ordre')
            ->get();

        return view('pages.partenaires', compact('partenaires'));
    }
}