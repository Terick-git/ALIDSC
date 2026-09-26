<?php

namespace App\Http\Controllers;

use App\Models\Adherent;
use App\Models\User;
use App\Notifications\NouvelleDemandeAdhesion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdhesionController extends Controller
{
    /**
     * Affiche le formulaire d'adhésion (page publique).
     */
    public function index()
    {
        return view('pages.adherer');
    }

    /**
     * Enregistre une nouvelle demande d'adhésion.
     *
     * RÈGLE MÉTIER : Aucune adhésion n'est finalisée automatiquement.
     * La demande est stockée avec le statut « en_attente » et le compte
     * n'est PAS actif. Un admin devra valider manuellement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'                 => 'required|string|max:255',
            'prenom'              => 'required|string|max:255',
            'date_naissance'      => 'required|date|before:today',
            'etablissement'       => 'required|string|max:255',
            'categorie'           => 'required|in:simple,actif',
            'cotisation_proposee' => 'nullable|integer|min:0|max:1000000',
            'piece_identite'      => 'required|file|mimetypes:application/pdf,image/jpeg,image/png|max:5120',
            'email'               => 'required|email|unique:adherents,email',
            'telephone'           => 'required|string|max:30',
            'adresse'             => 'required|string|max:255',
            'ville'               => 'nullable|string|max:100',
            'motivation'          => 'nullable|string|max:2000',
        ], [
            'email.unique'             => 'Cet email est déjà enregistré. Contactez-nous si besoin.',
            'piece_identite.required'  => 'Veuillez joindre une copie de votre pièce d\'identité.',
            'piece_identite.mimetypes' => 'Format accepté : PDF, JPG ou PNG.',
            'piece_identite.max'       => 'La pièce d\'identité ne doit pas dépasser 5 Mo.',
            'cotisation_proposee.integer' => 'Le montant doit être un nombre entier.',
        ]);

        try {
            $adherent = DB::transaction(function () use ($validated, $request) {
                // Stockage privé (hors du dossier public)
                $piecePath = $request->file('piece_identite')
                    ->store('pieces-identite', 'private');

                return Adherent::create([
                    'nom'                 => $validated['nom'],
                    'prenom'              => $validated['prenom'],
                    'date_naissance'      => $validated['date_naissance'],
                    'etablissement'       => $validated['etablissement'],
                    'profession'          => $validated['etablissement'],
                    'categorie'           => $validated['categorie'],
                    'cotisation_proposee' => $validated['cotisation_proposee'] ?? null,
                    'email'               => $validated['email'],
                    'telephone'           => $validated['telephone'],
                    'adresse'             => $validated['adresse'],
                    'ville'               => $validated['ville'] ?? null,
                    'piece_identite'      => $piecePath,
                    'notes'               => $validated['motivation'] ?? null,
                    'date_adhesion'       => now(),
                    'cotisation_montant'  => 0,
                    'statut_cotisation'   => 'en_attente',
                    'statut_demande'      => 'en_attente',
                    'actif'               => false,
                    'espace_actif'        => false,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Erreur création adhérent : ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withInput()
                ->withErrors(['email' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }

        // Notifier tous les administrateurs
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new NouvelleDemandeAdhesion($adherent));
            } catch (\Exception $e) {
                Log::error('Erreur notification admin : ' . $e->getMessage());
            }
        }

        return redirect()->route('adherer')
            ->with('success', 'Votre demande a bien été envoyée. Un responsable ALIDSC va l\'examiner et vous contactera par email.');
    }
}