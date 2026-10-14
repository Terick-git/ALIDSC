<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'nom', 'email', 'telephone',
        'montant', 'devise', 'moyen', 'statut',
        'cinetpay_transaction_id', 'cinetpay_payment_token',
        'cinetpay_payment_url', 'cinetpay_response',
        'paid_at', 'receipt_path', 'notes',
    ];

    protected $casts = [
        'montant' => 'integer',
        'cinetpay_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Génère une référence unique au format DON-20260929-A1B2C3.
     */
    public static function generateReference(): string
    {
        do {
            $reference = 'DON-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    public static function moyens(): array
    {
        return [
            'orange' => 'Orange Money',
            'mtn' => 'MTN Mobile Money',
            'carte' => 'Carte bancaire',
            'wave' => 'Wave',
            'autre' => 'Autre',
        ];
    }

    public static function statuts(): array
    {
        return [
            'en_attente' => 'En attente',
            'paye' => 'Payé',
            'echoue' => 'Échoué',
            'annule' => 'Annulé',
        ];
    }

    public function getMoyenLabelAttribute(): string
    {
        return self::moyens()[$this->moyen] ?? $this->moyen;
    }

    public function getStatutLabelAttribute(): string
    {
        return self::statuts()[$this->statut] ?? $this->statut;
    }

    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FCFA';
    }

    public function getKitEquivalentAttribute(): int
    {
        return max(1, (int) floor($this->montant / 5000));
    }

    public function getEstPayeAttribute(): bool
    {
        return $this->statut === 'paye';
    }
}