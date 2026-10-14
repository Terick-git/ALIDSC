<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActionController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $actions = Action::orderBy('ordre')->latest()->paginate(10);
        return view('admin.actions.index', compact('actions'));
    }

    public function create()
    {
        return view('admin.actions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'extrait' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug']  = $this->genererSlugUnique($validated['titre']);
        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'),
                'actions',
                1600,
                85
            );
        }

        Action::create($validated);

        return redirect()->route('admin.actions.index')
            ->with('success', 'Action créée avec succès.');
    }

    public function edit(Action $action)
    {
        return view('admin.actions.edit', compact('action'));
    }

    public function update(Request $request, Action $action)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'extrait' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('image')) {
            $this->images->supprimer($action->image);
            $validated['image'] = $this->images->uploadOptimise(
                $request->file('image'),
                'actions',
                1600,
                85
            );
        }

        $action->update($validated);

        return redirect()->route('admin.actions.index')
            ->with('success', 'Action mise à jour.');
    }

    public function destroy(Action $action)
    {
        $this->images->supprimer($action->image);
        $action->delete();

        return redirect()->route('admin.actions.index')
            ->with('success', 'Action supprimée.');
    }

    protected function genererSlugUnique(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;

        while (Action::where('slug', $slug)->exists()) {
            $slug = $base . '-' . Str::lower(Str::random(5));
        }

        return $slug;
    }
}