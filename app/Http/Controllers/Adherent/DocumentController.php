<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('actif', true)
            ->orderBy('categorie')
            ->orderBy('ordre')
            ->get()
            ->groupBy('categorie');

        return view('adherent.documents', compact('documents'));
    }

    public function download(Document $document)
    {
        if (!$document->actif || !Storage::disk('private')->exists($document->fichier)) {
            abort(404);
        }

        return Storage::disk('private')->download(
            $document->fichier,
            $document->slug . '.' . pathinfo($document->fichier, PATHINFO_EXTENSION)
        );
    }
}