<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    protected ImageService $images;

    public function __construct(ImageService $images)
    {
        $this->images = $images;
    }

    public function index()
    {
        $albums = Album::withCount('photos')->with('auteur')->latest()->paginate(15);
        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.albums.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'couverture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'date_evenement' => 'nullable|date',
            'lieu' => 'nullable|string|max:255',
            'ordre' => 'nullable|integer|min:0',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['titre']) . '-' . date('Ymd');
        $validated['user_id'] = Auth::id();
        $validated['publie'] = $request->has('publie');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('couverture')) {
            $validated['couverture'] = $this->images->uploadOptimise(
                $request->file('couverture'),
                'albums',
                1600,
                85
            );
        }

        $album = Album::create($validated);

        // Upload des photos multiples (une seule fois, optimisées)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $this->images->uploadOptimise(
                    $photo,
                    'albums/' . $album->id,
                    1600,
                    85
                );

                Photo::create([
                    'album_id' => $album->id,
                    'fichier' => $path,
                    'ordre' => $index,
                ]);
            }
        }

        return redirect()->route('admin.albums.edit', $album)
            ->with('success', 'Album créé. Vous pouvez maintenant ajouter ou modifier des photos.');
    }

    public function show(Album $album)
    {
        return redirect()->route('admin.albums.edit', $album);
    }

    public function edit(Album $album)
    {
        $album->load('photos');
        return view('admin.albums.edit', compact('album'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'couverture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'date_evenement' => 'nullable|date',
            'lieu' => 'nullable|string|max:255',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $validated['publie'] = $request->has('publie');
        $validated['ordre'] = $validated['ordre'] ?? 0;

        if ($request->hasFile('couverture')) {
            // Supprimer l'ancienne couverture
            $this->images->supprimer($album->couverture);

            $validated['couverture'] = $this->images->uploadOptimise(
                $request->file('couverture'),
                'albums',
                1600,
                85
            );
        }

        $album->update($validated);

        return redirect()->route('admin.albums.edit', $album)
            ->with('success', 'Album mis à jour.');
    }

    public function destroy(Album $album)
    {
        // Supprimer toutes les photos du dossier
        $dossier = 'albums/' . $album->id;
        if (Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->deleteDirectory($dossier);
        }
        // Supprimer la couverture
        $this->images->supprimer($album->couverture);

        $album->delete();

        return redirect()->route('admin.albums.index')
            ->with('success', 'Album supprimé.');
    }

    /**
     * Ajouter des photos à un album existant (optimisées)
     */
    public function ajouterPhotos(Request $request, Album $album)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $ordreActuel = $album->photos()->max('ordre') ?? -1;

        foreach ($request->file('photos') as $index => $photo) {
            $path = $this->images->uploadOptimise(
                $photo,
                'albums/' . $album->id,
                1600,
                85
            );

            Photo::create([
                'album_id' => $album->id,
                'fichier' => $path,
                'ordre' => $ordreActuel + $index + 1,
            ]);
        }

        return redirect()->route('admin.albums.edit', $album)
            ->with('success', count($request->file('photos')) . ' photo(s) ajoutée(s).');
    }

    /**
     * Mettre à jour la légende d'une photo
     */
    public function majPhoto(Request $request, Photo $photo)
    {
        $validated = $request->validate([
            'legende' => 'nullable|string|max:500',
        ]);

        $photo->update($validated);

        return redirect()->route('admin.albums.edit', $photo->album_id)
            ->with('success', 'Légende mise à jour.');
    }

    /**
     * Supprimer une photo
     */
    public function supprimerPhoto(Photo $photo)
    {
        $albumId = $photo->album_id;

        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($photo->fichier)) {
            Storage::disk('public')->delete($photo->fichier);
        }

        $photo->delete();

        return redirect()->route('admin.albums.edit', $albumId)
            ->with('success', 'Photo supprimée.');
    }
}