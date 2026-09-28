<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adherent;
use App\Notifications\AdhesionActivee;
use App\Notifications\DemandeAcceptee;
use App\Notifications\DemandeRefusee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdherentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Adherent::query();

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

        $adherents = $query->latest()->paginate(15);

        $stats = [
            'total'      => Adherent::count(),
            'a_jour'     => Adherent::where('statut_demande', 'active')->count(),
            'en_attente' => Adherent::where('statut_demande', 'en_attente')->count(),
            'expire'     => Adherent::where('statut_demande', 'expiree')->count(),
            'recettes'   => Adherent::where('statut_demande', 'active')->sum('cotisation_montant'),
        ];

        return view('admin.adherents.index', compact('adherents', 'stats'));
    }

    public function show(Adherent $adherent): View
    {
        return view('admin.adherents.show', compact('adherent'));
    }

    public function edit(Adherent $adherent): View
    {
        return view('admin.adherents.edit', compact('adherent'));
    }

    public function update(Request $request, Adherent $adherent): RedirectResponse
    {
        $validated = $request->validate([
            'nom'                   => ['required', 'string', 'max:255'],
            'prenom'                => ['required', 'string', 'max:255'],
            'date_naissance'        => ['nullable', 'date'],
            'email'                 => ['required', 'email', 'unique:adherents,email,' . $adherent->id],
            'telephone'             => ['required', 'string', 'max:30'],
            'ville'                 => ['nullable', 'string', 'max:100'],
            'adresse'               => ['nullable', 'string', 'max:255'],
            'etablissement'         => ['nullable', 'string', 'max:255'],
            'categorie'             => ['required', 'in:simple,actif'],
            'cotisation_proposee'   => ['nullable', 'integer', 'min:0'],
            'cotisation_montant'    => ['nullable', 'integer', 'min:0'],
            'statut_demande'        => ['required', 'in:brouillon,en_attente,acceptee,refuse,paiement_en_attente,paiement_validate,active,expiree'],
            'statut_cotisation'     => ['required', 'in:en_attente,a_jour,expire'],
            'motif_refus'           => ['nullable', 'string', 'max:2000'],
            'date_dernier_paiement' => ['nullable', 'date'],
            'mode_paiement'         => ['nullable', 'in:mobile_money,especes,virement,autre'],
            'notes'                 => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['actif'] = $request->has('actif');

        $adherent->update($validated);

        return redirect()
            ->route('admin.adherents.show', $adherent)
            ->with('success', 'Adhérent mis à jour.');
    }

    public function destroy(Adherent $adherent): RedirectResponse
    {
        // Nettoyage du fichier privé (pièce d'identité) avant suppression
        if ($adherent->piece_identite && Storage::disk('private')->exists($adherent->piece_identite)) {
            Storage::disk('private')->delete($adherent->piece_identite);
        }

        $adherent->delete();

        return redirect()
            ->route('admin.adherents.index')
            ->with('success', 'Adhérent supprimé.');
    }

    /**
     * Enregistrer un paiement de cotisation.
     */
    public function payer(Request $request, Adherent $adherent): RedirectResponse
    {
        $validated = $request->validate([
            'mode_paiement' => ['required', 'in:mobile_money,especes,virement,autre'],
            'montant'       => ['required', 'integer', 'min:1'],
        ]);

        $adherent->update([
            'statut_cotisation'     => 'a_jour',
            'date_dernier_paiement' => now(),
            'mode_paiement'         => $validated['mode_paiement'],
            'cotisation_montant'    => $validated['montant'],
        ]);

        return redirect()
            ->route('admin.adherents.show', $adherent)
            ->with('success', 'Paiement enregistré. Cotisation à jour.');
    }

    /**
     * Accepter une demande d'adhésion.
     */
    public function accepter(Adherent $adherent): RedirectResponse
    {
        $adherent->update([
            'statut_demande' => 'acceptee',
            'motif_refus'    => null,
        ]);

        try {
            $adherent->notify(new DemandeAcceptee($adherent));
        } catch (\Throwable $e) {
            Log::error('Notification acceptation échouée', [
                'adherent_id' => $adherent->id,
                'exception'   => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.adherents.show', $adherent)
            ->with('success', 'Demande acceptée. Un email a été envoyé à ' . $adherent->email . '.');
    }

    /**
     * Refuser une demande d'adhésion.
     */
    public function refuser(Request $request, Adherent $adherent): RedirectResponse
    {
        $validated = $request->validate([
            'motif_refus' => ['required', 'string', 'max:2000'],
        ]);

        $adherent->update([
            'statut_demande' => 'refuse',
            'motif_refus'    => $validated['motif_refus'],
        ]);

        try {
            $adherent->notify(new DemandeRefusee($adherent));
        } catch (\Throwable $e) {
            Log::error('Notification refus échouée', [
                'adherent_id' => $adherent->id,
                'exception'   => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.adherents.show', $adherent)
            ->with('success', 'Demande refusée. Un email a été envoyé à ' . $adherent->email . '.');
    }

    /**
     * Activer l'adhésion et envoyer les identifiants par email.
     */
    public function activer(Adherent $adherent): RedirectResponse
    {
        $motDePasse = Str::upper(Str::random(4)) . random_int(1000, 9999);

        $adherent->update([
            'statut_demande'        => 'active',
            'statut_cotisation'     => 'a_jour',
            'actif'                 => true,
            'espace_actif'          => true,
            'password'              => Hash::make($motDePasse),
            'date_dernier_paiement' => now(),
        ]);

        try {
            $adherent->notify(new AdhesionActivee($adherent, $motDePasse));
        } catch (\Throwable $e) {
            Log::error('Notification activation échouée', [
                'adherent_id' => $adherent->id,
                'exception'   => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('admin.adherents.show', $adherent)
            ->with('success', 'Adhésion activée. Mot de passe temporaire envoyé à ' . $adherent->email);
    }

    /**
     * Télécharger la pièce d'identité (route protégée admin).
     */
    public function telechargerPiece(Adherent $adherent)
    {
        if (! $adherent->piece_identite || ! Storage::disk('private')->exists($adherent->piece_identite)) {
            abort(404, 'Pièce d\'identité introuvable.');
        }

        $extension = pathinfo($adherent->piece_identite, PATHINFO_EXTENSION);

        return Storage::disk('private')->download(
            $adherent->piece_identite,
            'piece-identite-' . $adherent->id . '.' . $extension
        );
    }
}