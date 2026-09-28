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
        'date_dernier_paiement', 'mode_paiement', 'notes', 'actif',
        'espace_actif', 'derniere_connexion',
    ];

    protected $hidden = [
        'password', 'remember_token', 'piece_identite',
    ];

    protected $casts = [
        'date_adhesion' => 'date',
        'date_naissance' => 'date',
        'date_dernier_paiement' => 'date',
        'email_verified_at' => 'datetime',
        'derniere_connexion' => 'datetime',
        'actif' => 'boolean',
        'espace_actif' => 'boolean',
        'cotisation_montant' => 'integer',
        'cotisation_proposee' => 'integer',
    ];

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public static function statutsDemande(): array
    {
        return [
            'brouillon' => 'Brouillon',
            'en_attente' => 'En attente de validation',
            'acceptee' => 'Acceptée (en attente de paiement)',
            'refuse' => 'Refusée',
            'paiement_en_attente' => 'Paiement en attente',
            'paiement_validate' => 'Paiement validé',
            'active' => 'Adhésion active',
            'expiree' => 'Expirée',
        ];
    }

    public function getStatutDemandeLabelAttribute(): string
    {
        return self::statutsDemande()[$this->statut_demande] ?? $this->statut_demande;
    }

    public function getCategorieLabelAttribute(): string
    {
        return $this->categorie === 'actif' ? 'Membre actif' : 'Membre simple';
    }

    // Peut accéder à l'espace adhérent si le compte est activé
    public function peutAccederEspace(): bool
    {
        return $this->espace_actif && $this->statut_demande === 'active';
    }
}