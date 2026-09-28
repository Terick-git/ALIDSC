<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use App\Models\Message;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function adherents(Request $request): StreamedResponse
    {
        $query = Adherent::query();

        if ($request->filled('statut')) {
            $query->where('statut_cotisation', $request->statut);
        }

        $adherents = $query->orderBy('nom')->get();

        $filename = 'adherents-alidsc-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($adherents) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel (accents corrects)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($handle, [
                'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Ville', 'Établissement',
                'Date adhésion', 'Montant cotisation (FCFA)', 'Statut cotisation',
                'Dernier paiement', 'Mode paiement', 'Actif', 'Notes'
            ], ';');

            foreach ($adherents as $a) {
                fputcsv($handle, [
                    $a->id,
                    $a->nom,
                    $a->prenom,
                    $a->email,
                    $a->telephone,
                    $a->ville,
                    $a->etablissement,
                    $a->date_adhesion?->format('d/m/Y'),
                    $a->cotisation_montant,
                    $a->statut_cotisation,
                    $a->date_dernier_paiement?->format('d/m/Y'),
                    $a->mode_paiement,
                    $a->actif ? 'Oui' : 'Non',
                    $a->notes,
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function messages(Request $request): StreamedResponse
    {
        $messages = Message::orderByDesc('created_at')->get();

        $filename = 'messages-alidsc-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($messages) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID', 'Date', 'Nom', 'Email', 'Téléphone', 'Sujet', 'Message', 'Lu'
            ], ';');

            foreach ($messages as $m) {
                fputcsv($handle, [
                    $m->id,
                    $m->created_at->format('d/m/Y H:i'),
                    $m->nom,
                    $m->email,
                    $m->telephone,
                    $m->sujet,
                    $m->contenu,
                    $m->lu ? 'Oui' : 'Non',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}