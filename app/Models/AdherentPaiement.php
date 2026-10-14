<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdherentPaiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'adherent_id', 'reference', 'montant', 'mode_paiement',
        'statut', 'paid_at', 'receipt_path', 'notes', 'enregistre_par',
    ];

    protected $casts = [
        'montant' => 'integer',
        'paid_at' => 'datetime',
    ];

    // ─── Relations ────────────────────────────────────────

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }

    public function enregistrePar()
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    // ─── Référence ────────────────────────────────────────

    public static function generateReference(): string
    {
        do {
            $reference = 'COT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    // ─── Accessors ────────────────────────────────────────

    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    public function getModePaiementLabelAttribute(): string
    {
        return match ($this->mode_paiement) {
            'mobile_money' => 'Mobile Money',
            'especes'      => 'Espèces',
            'virement'     => 'Virement',
            'autre'        => 'Autre',
            default        => $this->mode_paiement,
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'valide'     => 'Validé',
            'annule'     => 'Annulé',
            default      => $this->statut,
        };
    }

    public function getEstValideAttribute(): bool
    {
        return $this->statut === 'valide';
    }
}
