<?php

namespace App\Http\Controllers\Adherent;

use App\Http\Controllers\Controller;
use App\Services\ReceiptService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CotisationController extends Controller
{
    public function __construct(
        protected ReceiptService $receipts
    ) {}

    /**
     * Page "Ma cotisation" : statut + historique.
     */
    public function index()
    {
        $adherent = Auth::guard('adherent')->user();

        $paiements = $adherent->paiements()
            ->where('statut', 'valide')
            ->get();

        $totalPaye = $paiements->sum('montant');

        $prochaineEcheance = $adherent->date_dernier_paiement
            ? $adherent->date_dernier_paiement->copy()->addYear()
            : null;

        return view('adherent.cotisation', compact(
            'adherent',
            'paiements',
            'totalPaye',
            'prochaineEcheance'
        ));
    }

    /**
     * Télécharger le reçu PDF d'un paiement.
     */
    public function recu(int $paiementId): Response
    {
        $adherent = Auth::guard('adherent')->user();

        $paiement = $adherent->paiements()
            ->where('id', $paiementId)
            ->where('statut', 'valide')
            ->firstOrFail();

        // Génère le reçu s'il n'existe pas
        if (! $paiement->receipt_path || ! Storage::disk('private')->exists($paiement->receipt_path)) {
            $path = $this->receipts->genererPourPaiement($paiement);

            if (! $path) {
                abort(500, 'Impossible de générer le reçu. Contactez-nous à contact@alidsc.org.');
            }

            $paiement->refresh();
        }

        $contenu = Storage::disk('private')->get($paiement->receipt_path);

        return response($contenu, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="recu-cotisation-' . $paiement->reference . '.pdf"',
        ]);
    }
}
