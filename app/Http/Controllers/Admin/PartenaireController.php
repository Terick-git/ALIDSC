<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartenaireController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $partenaires = Partenaire::orderBy('ordre')->paginate(15);
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function create()
    {
        return view('admin.partenaires.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'site_web' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:2000',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug']  = $this->genererSlugUnique($validated['nom']);
        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->images->uploadOptimise(
                $request->file('logo'),
                'partenaires',
                600,
                90
            );
        }

        Partenaire::create($validated);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire ajouté.');
    }

    public function edit(Partenaire $partenaire)
    {
        return view('admin.partenaires.edit', compact('partenaire'));
    }

    public function update(Request $request, Partenaire $partenaire)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'site_web' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'telephone' => 'nullable|string|max:30',
            'description' => 'nullable|string|max:2000',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('logo')) {
            $this->images->supprimer($partenaire->logo);
            $validated['logo'] = $this->images->uploadOptimise(
                $request->file('logo'),
                'partenaires',
                600,
                90
            );
        }

        $partenaire->update($validated);

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partenaire $partenaire)
    {
        $this->images->supprimer($partenaire->logo);
        $partenaire->delete();

        return redirect()->route('admin.partenaires.index')
            ->with('success', 'Partenaire supprimé.');
    }

    protected function genererSlugUnique(string $nom): string
    {
        $base = Str::slug($nom);
        $slug = $base;

        while (Partenaire::where('slug', $slug)->exists()) {
            $slug = $base . '-' . Str::lower(Str::random(5));
        }

        return $slug;
    }
}