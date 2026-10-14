<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Adherent extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'email', 'password', 'telephone',
        'ville', 'adresse', 'etablissement', 'categorie',
        'cotisation_proposee', 'date_adhesion', 'cotisation_montant',
        'statut_cotisation', 'statut_demande', 'motif_refus', 'piece_identite',
        'date_dernier_paiement', 'rappel_cotisation_envoye_le',
        'mode_paiement', 'notes', 'actif',
        'espace_actif', 'derniere_connexion', 'must_change_password',
    ];

    protected $hidden = [
        'password', 'remember_token', 'piece_identite',
    ];

    protected $casts = [
        'date_adhesion'                 => 'date',
        'date_naissance'                => 'date',
        'date_dernier_paiement'         => 'date',
        'rappel_cotisation_envoye_le'   => 'datetime',
        'email_verified_at'             => 'datetime',
        'derniere_connexion'            => 'datetime',
        'actif'                         => 'boolean',
        'espace_actif'                  => 'boolean',
        'must_change_password'          => 'boolean',
        'cotisation_montant'            => 'integer',
        'cotisation_proposee'           => 'integer',
    ];

    // ─── Relations ────────────────────────────────────────

    public function paiements()
    {
        return $this->hasMany(AdherentPaiement::class)->latest();
    }

    // ─── Accessors ────────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getStatutDemandeLabelAttribute(): string
    {
        return self::statutsDemande()[$this->statut_demande] ?? $this->statut_demande;
    }

    public function getCategorieLabelAttribute(): string
    {
        return $this->categorie === 'actif' ? 'Membre actif' : 'Membre simple';
    }

    /**
     * Date d'expiration de la cotisation (1 an après le dernier paiement).
     */
    public function getDateExpirationCotisationAttribute(): ?\Carbon\Carbon
    {
        return $this->date_dernier_paiement
            ? $this->date_dernier_paiement->copy()->addYear()
            : null;
    }

    /**
     * Nombre de jours restants avant expiration (null si pas de paiement).
     */
    public function getJoursAvantExpirationAttribute(): ?int
    {
        $expiration = $this->getDateExpirationCotisationAttribute();

        if (! $expiration) {
            return null;
        }

        return max(0, now()->diffInDays($expiration, false));
    }

    // ─── Méthodes ─────────────────────────────────────────

    public static function statutsDemande(): array
    {
        return [
            'brouillon'           => 'Brouillon',
            'en_attente'          => 'En attente de validation',
            'acceptee'            => 'Acceptée (en attente de paiement)',
            'refuse'              => 'Refusée',
            'paiement_en_attente' => 'Paiement en attente',
            'paiement_validate'   => 'Paiement validé',
            'active'              => 'Adhésion active',
            'expiree'             => 'Expirée',
        ];
    }

    public function peutAccederEspace(): bool
    {
        return $this->espace_actif && $this->statut_demande === 'active';
    }

    public function aPaiementCetteAnnee(): bool
    {
        return $this->paiements()
            ->where('statut', 'valide')
            ->where('paid_at', '>=', now()->subYear())
            ->exists();
    }

    /**
     * Scope : adhérents ayant accès à l'espace (pour les notifications).
     */
    public function scopeActifs($query)
    {
        return $query->where('espace_actif', true)
            ->where('statut_demande', 'active');
    }
}