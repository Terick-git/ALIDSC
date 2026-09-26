<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Temoignage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class TemoignageController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $temoignages = Temoignage::orderBy('ordre')->paginate(15);
        return view('admin.temoignages.index', compact('temoignages'));
    }

    public function create()
    {
        return view('admin.temoignages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'citation' => 'required|string|max:1000',
            'categorie' => 'required|in:eleve,parent,membre,partenaire',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->images->uploadVignette(
                $request->file('photo'),
                'temoignages',
                400
            );
        }

        Temoignage::create($validated);

        return redirect()->route('admin.temoignages.index')
            ->with('success', 'Témoignage ajouté.');
    }

    public function edit(Temoignage $temoignage)
    {
        return view('admin.temoignages.edit', compact('temoignage'));
    }

    public function update(Request $request, Temoignage $temoignage)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'citation' => 'required|string|max:1000',
            'categorie' => 'required|in:eleve,parent,membre,partenaire',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            $this->images->supprimer($temoignage->photo);

            $validated['photo'] = $this->images->uploadVignette(
                $request->file('photo'),
                'temoignages',
                400
            );
        }

        $temoignage->update($validated);

        return redirect()->route('admin.temoignages.index')
            ->with('success', 'Témoignage mis à jour.');
    }

    public function destroy(Temoignage $temoignage)
    {
        // Supprimer la photo
        $this->images->supprimer($temoignage->photo);

        $temoignage->delete();

        return redirect()->route('admin.temoignages.index')
            ->with('success', 'Témoignage supprimé.');
    }
}