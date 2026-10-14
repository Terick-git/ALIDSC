<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('auteur')->orderBy('categorie')->orderBy('ordre')->paginate(15);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'categorie' => 'required|string|in:general,ag,statuts,rapports,formulaires',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:20480',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['slug']    = $this->genererSlugUnique($validated['titre']);
        $validated['actif']   = $request->has('actif');
        $validated['ordre']   = $validated['ordre'] ?? 0;
        $validated['user_id'] = auth()->id();
        $validated['fichier'] = $request->file('fichier')->store('documents', 'private');

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document ajouté.');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'categorie' => 'required|string|in:general,ag,statuts,rapports,formulaires',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:20480',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['actif'] = $request->has('actif');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('fichier')) {
            if ($document->fichier && Storage::disk('private')->exists($document->fichier)) {
                Storage::disk('private')->delete($document->fichier);
            }
            $validated['fichier'] = $request->file('fichier')->store('documents', 'private');
        }

        $document->update($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document mis à jour.');
    }

    public function destroy(Document $document)
    {
        if ($document->fichier && Storage::disk('private')->exists($document->fichier)) {
            Storage::disk('private')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document supprimé.');
    }

    protected function genererSlugUnique(string $titre): string
    {
        $base = Str::slug($titre);
        $slug = $base;

        while (Document::where('slug', $slug)->exists()) {
            $slug = $base . '-' . Str::lower(Str::random(5));
        }

        return $slug;
    }
}