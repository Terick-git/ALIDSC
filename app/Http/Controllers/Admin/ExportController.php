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

                // Filtre par IDs (utilisé par les actions bulk)
        if ($request->filled('ids')) {
            $ids = array_values(array_unique(array_filter(
                array_map('intval', explode(',', (string) $request->ids)),
                fn ($id) => $id > 0
            )));

            if (! empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        // Le formulaire de filtrage envoie `statut` = statut_demande
        if ($request->filled('statut')) {
            $query->where('statut_demande', $request->statut);
        }

        if ($request->filled('recherche')) {
            $q = $request->recherche;
            $query->where(function ($w) use ($q) {
                $w->where('nom', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telephone', 'like', "%{$q}%");
            });
        }

        $adherents = $query->orderBy('nom')->get();

        $filename = 'adherents-alidsc-' . date('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($adherents) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel (accents corrects)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Ville', 'Établissement',
                'Date adhésion', 'Montant cotisation (FCFA)', 'Statut cotisation',
                'Statut demande', 'Dernier paiement', 'Mode paiement', 'Actif', 'Notes'
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
                    $a->statut_demande,
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
