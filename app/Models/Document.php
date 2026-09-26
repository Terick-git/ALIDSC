<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    protected $fillable = [
        'titre', 'slug', 'description', 'fichier',
        'categorie', 'ordre', 'actif', 'user_id',
    ];

    protected $casts = ['actif' => 'boolean', 'ordre' => 'integer'];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($document) {
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->titre);
            }
        });
    }

    public static function categories(): array
    {
        return [
            'general' => 'Général',
            'ag' => 'Comptes-rendus d\'AG',
            'statuts' => 'Statuts & Règlement',
            'rapports' => 'Rapports d\'activité',
            'formulaires' => 'Formulaires',
        ];
    }

    public function getCategorieLabelAttribute(): string
    {
        return self::categories()[$this->categorie] ?? $this->categorie;
    }
}