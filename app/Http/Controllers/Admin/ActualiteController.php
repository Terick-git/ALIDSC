<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActualiteController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $actualites = Actualite::with('auteur')->latest()->paginate(10);
        return view('admin.actualites.index', compact('actualites'));
    }

    public function create()
    {
        return view('admin.actualites.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'    => 'required|string|max:255',
            'extrait'  => 'required|string|max:500',
            'contenu'  => 'required|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'publie'   => 'boolean',
            'interne'  => 'boolean',
        ]);

        // Slug unique : slug + suffixe aléatoire pour éviter les collisions
        $validated['slug']    = $this->genererSlugUnique($validated['titre']);
        $validated['user_id'] = Auth::id();
        $validated['publie']  = $request->boolean('publie');
        $validated['interne'] = $request->boolean('interne');

        if ($request->hasFile('image')) {
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'), 'actualites', 1600, 85
            );
        }

        if ($validated['publie']) {
            $validated['publie_le'] = now();
        }

        Actualite::create($validated);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité créée avec succès.');
    }

    public function edit(Actualite $actualite)
    {
        return view('admin.actualites.edit', compact('actualite'));
    }

    public function update(Request $request, Actualite $actualite)
    {
        $validated = $request->validate([
            'titre'    => 'required|string|max:255',
            'extrait'  => 'required|string|max:500',
            'contenu'  => 'required|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'publie'   => 'boolean',
            'interne'  => 'boolean',
        ]);

        $validated['publie']  = $request->boolean('publie');
        $validated['interne'] = $request->boolean('interne');

        if ($validated['publie'] && !$actualite->publie_le) {
            $validated['publie_le'] = now();
        }

        if ($request->hasFile('image')) {
            $this->images->supprimer($actualite->image);
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'), 'actualites', 1600, 85
            );
        }

        $actualite->update($validated);

        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité mise à jour.');
    }

    public function destroy(Actualite $actualite)
    {
        $this->images->supprimer($actualite->image);
        $actualite->delete();
        return redirect()->route('admin.actualites.index')
            ->with('success', 'Actualité supprimée.');
    }

    /**
     * Génère un slug unique (suffixe aléatoire si collision).
     */
    protected function genererSlugUnique(string $titre, ?int $ignoreId = null): string
    {
        $base = Str::slug($titre);
        $slug = $base;

        while (
            Actualite::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . Str::lower(Str::random(5));
        }

        return $slug;
    }
}