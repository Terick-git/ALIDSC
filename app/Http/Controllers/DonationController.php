<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Notifications\ConfirmationDon;
use App\Services\CinetPayService;
use App\Services\ReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        protected CinetPayService $cinetpay,
        protected ReceiptService $receipts,
    ) {}

    /**
     * POST /don — point d'entrée du formulaire
     */
    public function traiter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'montant'   => ['required', 'integer', 'min:500', 'max:5000000'],
            'moyen'     => ['required', 'in:orange,mtn,carte,wave,autre'],
            'nom'       => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
            'email'     => ['required', 'email', 'max:255'],
        ], [
            'montant.required'   => 'Veuillez choisir un montant.',
            'montant.integer'    => 'Le montant doit être un nombre entier.',
            'montant.min'        => 'Le montant minimum est de 500 FCFA.',
            'montant.max'        => 'Le montant maximum est de 5 000 000 FCFA.',
            'moyen.required'     => 'Veuillez choisir un moyen de paiement.',
            'nom.required'       => 'Votre nom est obligatoire.',
            'telephone.required' => 'Votre téléphone est obligatoire.',
            'email.required'     => 'Votre email est obligatoire pour recevoir le reçu.',
        ]);

        $donation = Donation::create([
            'reference' => Donation::generateReference(),
            'nom'       => $validated['nom'],
            'email'     => $validated['email'],
            'telephone' => $validated['telephone'],
            'montant'   => $validated['montant'],
            'devise'    => 'XOF',
            'moyen'     => $validated['moyen'],
            'statut'    => 'en_attente',
        ]);

        // Mode simulation si CinetPay n'est pas configuré
        if (! config('services.cinetpay.apikey') || ! config('services.cinetpay.site_id')) {
            Log::info('Donation créée (mode simulation, CinetPay non configuré)', [
                'reference' => $donation->reference,
                'montant'   => $donation->montant,
            ]);

            return redirect()
                ->route('don')
                ->with('success', sprintf(
                    'Merci %s ! Votre don de %s a bien été enregistré (référence %s). ' .
                    'L\'intégration CinetPay sera finalisée prochainement.',
                    $donation->nom,
                    $donation->montant_formate,
                    $donation->reference
                ));
        }

        // Appel CinetPay
        $result = $this->cinetpay->initialiser($donation);

        if (! $result['success']) {
            $donation->update([
                'statut' => 'echoue',
                'notes'  => $result['message'],
            ]);

            return redirect()
                ->route('don')
                ->with('error', 'Le paiement n\'a pas pu être initié : ' . $result['message']);
        }

        $donation->update([
            'cinetpay_payment_token'  => $result['token'] ?? null,
            'cinetpay_payment_url'    => $result['payment_url'] ?? null,
            'cinetpay_transaction_id' => $donation->reference,
        ]);

        return redirect()->away($result['payment_url']);
    }

    /**
     * GET /don/merci/{reference} — page de confirmation
     */
    public function merci(string $reference): View
    {
        $donation = Donation::where('reference', $reference)->firstOrFail();

        // Vérification CinetPay au retour (au cas où le webhook n'a pas encore frappé)
        if ($donation->statut === 'en_attente' && config('services.cinetpay.apikey')) {
            $check = $this->cinetpay->verifier($donation->reference);

            if ($check['success'] && $check['statut'] === 'ACCEPTED') {
                $this->marquerPaye($donation, $check['data']);
                $donation->refresh();
            }
        }

        return view('pages.don-merci', compact('donation'));
    }

    /**
     * POST /don/webhook — notification asynchrone de CinetPay
     */
    public function webhook(Request $request): \Illuminate\Http\Response
    {
        // ⚠️ Ne JAMAIS logger le payload complet : il peut contenir
        //    des données personnelles du donateur (nom, téléphone, email).
        Log::info('CinetPay webhook reçu', [
            'cpm_trans_id'      => $request->input('cpm_trans_id'),
            'cpm_result'        => $request->input('cpm_result'),
            'cpm_error_message' => $request->input('cpm_error_message'),
        ]);

        $reference = $request->input('cpm_trans_id') ?? $request->input('transaction_id');

        if (! $reference) {
            return response('Reference manquante', 400);
        }

        $donation = Donation::where('reference', $reference)->first();

        if (! $donation) {
            return response('Donation introuvable', 404);
        }

        $check = $this->cinetpay->verifier($reference);

        if ($check['success'] && $check['statut'] === 'ACCEPTED') {
            $this->marquerPaye($donation, $check['data']);
        } elseif (in_array($check['statut'], ['REFUSED', 'CANCELED', 'FAILED'])) {
            $donation->update([
                'statut' => $check['statut'] === 'CANCELED' ? 'annule' : 'echoue',
                'cinetpay_response' => $check['data'],
            ]);
        }

        return response('OK', 200);
    }

    /**
     * Marque le don comme payé + envoie l'email de confirmation.
     *
     * Idempotent : un verrou pessimiste (lockForUpdate) protège contre
     * deux webhooks simultanés qui déclencheraient deux fois reçu + email.
     */
    protected function marquerPaye(Donation $donation, array $data): void
    {
        DB::transaction(function () use ($donation, $data) {
            $fresh = Donation::where('id', $donation->id)
                ->lockForUpdate()
                ->first();

            if (! $fresh || $fresh->statut === 'paye') {
                return; // Déjà traité ou don introuvable
            }

            $fresh->update([
                'statut'            => 'paye',
                'paid_at'           => now(),
                'cinetpay_response' => $data,
            ]);

            // Génère le reçu PDF
            try {
                $this->receipts->generer($fresh);
            } catch (\Throwable $e) {
                Log::error('Génération reçu échouée', [
                    'donation_id' => $fresh->id,
                    'exception'   => $e->getMessage(),
                ]);
            }

            // Notifie le donateur (sera mis en file d'attente via ShouldQueue)
            try {
                $fresh->notify(new ConfirmationDon($fresh));
            } catch (\Throwable $e) {
                Log::error('Envoi email confirmation don échoué', [
                    'donation_id' => $fresh->id,
                    'exception'   => $e->getMessage(),
                ]);
            }
        });
    }

    /**
     * GET /don/recu/{reference} — téléchargement du reçu PDF.
     *
     * Protégé par une URL signée (voir routes/web.php).
     */
    public function recu(string $reference)
    {
        $donation = Donation::where('reference', $reference)->firstOrFail();

        if (! $donation->est_paye) {
            abort(404, 'Le reçu n\'est disponible qu\'après confirmation du paiement.');
        }

        $contenu = $this->receipts->obtenir($donation);

        if (! $contenu) {
            abort(500, 'Impossible de générer le reçu. Contactez-nous à contact@alidsc.org.');
        }

        $nomFichier = 'recu-alidsc-' . $donation->reference . '.pdf';

        return response($contenu, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nomFichier . '"',
        ]);
    }
}