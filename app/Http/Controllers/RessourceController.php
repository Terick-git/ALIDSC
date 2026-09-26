<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;   // ← AJOUT CRITIQUE

class RessourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Ressource::where('actif', true)->where('public', true);

        if ($request->filled('categorie')) $query->where('categorie', $request->categorie);
        if ($request->filled('annee'))     $query->where('annee', $request->annee);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($w) => $w->where('titre', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->orWhere('auteur', 'like', "%{$q}%"));
        }

        $ressources = $query->orderBy('ordre')
            ->orderByDesc('annee')
            ->orderByDesc('created_at')
            ->paginate(15);

        $categories = Ressource::categories();
        $annees = Ressource::where('actif', true)->where('public', true)
            ->whereNotNull('annee')->distinct()->orderByDesc('annee')->pluck('annee');

        return view('pages.ressources', compact('ressources', 'categories', 'annees'));
    }

    public function show($slug)
    {
        $ressource = Ressource::where('slug', $slug)
            ->where('actif', true)->where('public', true)
            ->firstOrFail();

        $similaires = Ressource::where('categorie', $ressource->categorie)
            ->where('id', '!=', $ressource->id)
            ->where('actif', true)->where('public', true)
            ->take(3)->get();

        return view('pages.ressource', compact('ressource', 'similaires'));
    }

    public function telecharger($slug)
    {
        $ressource = Ressource::where('slug', $slug)
            ->where('actif', true)->where('public', true)
            ->firstOrFail();

        if (!Storage::disk('private')->exists($ressource->fichier)) {
            abort(404, 'Fichier introuvable.');
        }

        $ressource->increment('telechargements');

        $extension = pathinfo($ressource->fichier, PATHINFO_EXTENSION);
        $nom = Str::slug($ressource->titre) . '.' . $extension;

        return Storage::disk('private')->download($ressource->fichier, $nom);
    }
}