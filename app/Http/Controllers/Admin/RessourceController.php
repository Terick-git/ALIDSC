<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RessourceController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index(Request $request)
    {
        $query = Ressource::query();

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $ressources = $query->latest()->paginate(15);
        $categories = Ressource::categories();

        return view('admin.ressources.index', compact('ressources', 'categories'));
    }

    public function create()
    {
        return view('admin.ressources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
            'categorie' => 'required|in:etude,publication,rapport,journal,educative',
            'auteur' => 'nullable|string|max:255',
            'annee' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'couverture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['titre']) . '-' . uniqid();
        $validated['user_id'] = Auth::id();
        $validated['public'] = $request->has('public');
        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;
        $validated['fichier'] = $request->file('fichier')->store('ressources', 'private');

        if ($request->hasFile('couverture')) {
            $validated['couverture'] = $this->images->uploadOptimise(
                $request->file('couverture'),
                'ressources/couvertures',
                800,
                85
            );
        }

        Ressource::create($validated);

        return redirect()->route('admin.ressources.index')
            ->with('success', 'Ressource ajoutée.');
    }

    public function edit(Ressource $ressource)
    {
        return view('admin.ressources.edit', compact('ressource'));
    }

    public function update(Request $request, Ressource $ressource)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:20480',
            'categorie' => 'required|in:etude,publication,rapport,journal,educative',
            'auteur' => 'nullable|string|max:255',
            'annee' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'couverture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['public'] = $request->has('public');
        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        // Remplacement du fichier principal
        if ($request->hasFile('fichier')) {
            if ($ressource->fichier && Storage::disk('private')->exists($ressource->fichier)) {
                Storage::disk('private')->delete($ressource->fichier);
            }
            $validated['fichier'] = $request->file('fichier')->store('ressources', 'private');
        }

        // Remplacement de la couverture
        if ($request->hasFile('couverture')) {
            $this->images->supprimer($ressource->couverture);

            $validated['couverture'] = $this->images->uploadOptimise(
                $request->file('couverture'),
                'ressources/couvertures',
                800,
                85
            );
        }

        $ressource->update($validated);

        return redirect()->route('admin.ressources.index')
            ->with('success', 'Ressource mise à jour.');
    }

    public function destroy(Ressource $ressource)
    {
        // Supprimer le fichier principal
        if ($ressource->fichier && Storage::disk('private')->exists($ressource->fichier)) {
            Storage::disk('private')->delete($ressource->fichier);
        }
        // Supprimer la couverture
        $this->images->supprimer($ressource->couverture);

        $ressource->delete();

        return redirect()->route('admin.ressources.index')
            ->with('success', 'Ressource supprimée.');
    }
}