<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    protected $fillable = [
        'nom', 'role', 'photo', 'citation', 'categorie', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    public static function categories(): array
    {
        return [
            'eleve' => 'Élève',
            'parent' => 'Parent',
            'membre' => 'Membre ALIDSC',
            'partenaire' => 'Partenaire',
        ];
    }

    public function getCategorieLabelAttribute(): string
    {
        return self::categories()[$this->categorie] ?? $this->categorie;
    }
}