<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use App\Models\Evenement;
use App\Notifications\NouvelEvenementAdherent;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class EvenementController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $evenements = Evenement::with('auteur')->latest('date_debut')->paginate(15);
        return view('admin.evenements.index', compact('evenements'));
    }

    public function create()
    {
        return view('admin.evenements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'extrait' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'ville' => 'nullable|string|max:100',
            'intervenants' => 'nullable|string|max:500',
            'lien_inscription' => 'nullable|url|max:500',
            'statut' => 'required|in:brouillon,publie,annule',
        ]);

        $validated['slug']    = $this->genererSlugUnique($validated['titre']);
        $validated['user_id'] = Auth::id();
        $validated['inscription_requise'] = $request->has('inscription_requise');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'),
                'evenements',
                1600,
                85
            );
        }

        $evenement = Evenement::create($validated);

        // Notification si publié dès la création
        if ($evenement->statut === 'publie') {
            $this->notifierAdherents($evenement);
        }

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement créé avec succès.' . ($evenement->statut === 'publie' ? ' Adhérents notifiés.' : ''));
    }

    public function edit(Evenement $evenement)
    {
        return view('admin.evenements.edit', compact('evenement'));
    }

    public function update(Request $request, Evenement $evenement)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'extrait' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'lieu' => 'required|string|max:255',
            'ville' => 'nullable|string|max:100',
            'intervenants' => 'nullable|string|max:500',
            'lien_inscription' => 'nullable|url|max:500',
            'statut' => 'required|in:brouillon,publie,annule',
        ]);

        $statutAvant = $evenement->statut;

        $validated['inscription_requise'] = $request->has('inscription_requise');

        if ($request->hasFile('image')) {
            $this->images->supprimer($evenement->image);
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'),
                'evenements',
                1600,
                85
            );
        }

        $evenement->update($validated);

        // Notification uniquement si l'événement vient d'être publié
        // (passage brouillon → publie, ou annule → publie)
        if ($statutAvant !== 'publie' && $evenement->statut === 'publie') {
            $this->notifierAdherents($evenement);
        }

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement mis à jour.');
    }

    public function destroy(Evenement $evenement)
    {
        $this->images->supprimer($evenement->image);
        $evenement->delete();

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement supprimé.');
    }

    /**
     * Notifie tous les adhérents actifs (en queue).
     */
    protected function notifierAdherents(Evenement $evenement): void
    {
        try {
            $adherents = Adherent::actifs()->get();

            if ($adherents->isEmpty()) {
                return;
            }

            Notification::send($adherents, new NouvelEvenementAdherent($evenement));
        } catch (\Throwable $e) {
            Log::error('Notification événement aux adhérents échouée', [
                'evenement_id' => $evenement->id,
                'exception'    => $e->getMessage(),
            ]);
        }
    }

    protected function genererSlugUnique(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;

        while (Evenement::where('slug', $slug)->exists()) {
            $slug = $base . '-' . Str::lower(Str::random(5));
        }

        return $slug;
    }
}