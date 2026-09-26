<?php

namespace App\Http\Controllers;

use App\Models\Album;

class GalerieController extends Controller
{
    public function index()
    {
        $albums = Album::where('publie', true)
            ->withCount('photos')
            ->orderBy('ordre')
            ->orderByDesc('date_evenement')
            ->paginate(12);

        return view('pages.galerie', compact('albums'));
    }

    public function show($slug)
    {
        $album = Album::where('slug', $slug)
            ->where('publie', true)
            ->with('photos')
            ->firstOrFail();

        $autresAlbums = Album::where('publie', true)
            ->where('id', '!=', $album->id)
            ->orderByDesc('date_evenement')
            ->take(3)
            ->get();

        return view('pages.album', compact('album', 'autresAlbums'));
    }
}