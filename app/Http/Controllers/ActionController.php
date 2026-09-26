<?php

namespace App\Http\Controllers;

use App\Models\Action;

class ActionController extends Controller
{
    public function index()
{
    $actions = Action::where('actif', true)
        ->orderBy('ordre')
        ->latest()
        ->get();

    // ← CHEMIN MODIFIÉ
    return view('pages.actions', compact('actions'));
}

public function show($slug)
{
    $action = Action::where('slug', $slug)
        ->where('actif', true)
        ->firstOrFail();

    // ← CHEMIN MODIFIÉ
    return view('pages.action', compact('action'));
}
}
