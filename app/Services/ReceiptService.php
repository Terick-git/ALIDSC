<?php

namespace App\Services;

use App\Models\AdherentPaiement;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    /**
     * Génère le PDF du reçu de don et le stocke sur le disque privé.
     */
    public function generer(Donation $donation): ?string
    {
        if (! $donation->est_paye) {
            return null;
        }

        try {
            $pdf = Pdf::loadView('pdfs.recu-don', [
                'donation' => $donation,
            ])->setPaper('a4', 'portrait');

            $chemin = 'recus/' . $donation->reference . '.pdf';

            Storage::disk('private')->put($chemin, $pdf->output());

            $donation->update(['receipt_path' => $chemin]);

            return $chemin;
        } catch (\Throwable $e) {
            Log::error('Génération reçu PDF don échouée', [
                'donation_id' => $donation->id,
                'exception'   => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Génère le PDF du reçu de cotisation adhérent.
     */
    public function genererPourPaiement(AdherentPaiement $paiement): ?string
    {
        if ($paiement->statut !== 'valide') {
            return null;
        }

        try {
            $pdf = Pdf::loadView('pdfs.recu-cotisation', [
                'paiement' => $paiement,
                'adherent' => $paiement->adherent,
            ])->setPaper('a4', 'portrait');

            $chemin = 'recus-cotisations/' . $paiement->reference . '.pdf';

            Storage::disk('private')->put($chemin, $pdf->output());

            $paiement->update(['receipt_path' => $chemin]);

            return $chemin;
        } catch (\Throwable $e) {
            Log::error('Génération reçu PDF cotisation échouée', [
                'paiement_id' => $paiement->id,
                'exception'   => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Retourne le contenu binaire du PDF d'un don.
     */
    public function obtenir(Donation $donation): ?string
    {
        if (! $donation->receipt_path ||
            ! Storage::disk('private')->exists($donation->receipt_path)) {
            $this->generer($donation);
            $donation->refresh();
        }

        if (! $donation->receipt_path ||
            ! Storage::disk('private')->exists($donation->receipt_path)) {
            return null;
        }

        return Storage::disk('private')->get($donation->receipt_path);
    }
}
