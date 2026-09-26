<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ressource extends Model
{
    protected $fillable = [
        'titre', 'slug', 'description', 'fichier', 'categorie', 'auteur',
        'annee', 'couverture', 'telechargements', 'public', 'ordre', 'actif', 'user_id',
    ];

    protected $casts = [
        'public' => 'boolean',
        'actif' => 'boolean',
        'ordre' => 'integer',
        'annee' => 'integer',
        'telechargements' => 'integer',
    ];

    public function auteurRelation()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($ressource) {
            if (empty($ressource->slug)) {
                $ressource->slug = Str::slug($ressource->titre) . '-' . uniqid();
            }
        });
    }

    public static function categories(): array
    {
        return [
            'etude' => 'Études',
            'publication' => 'Publications',
            'rapport' => 'Rapports',
            'journal' => 'Journaux d\'information',
            'educative' => 'Ressources éducatives',
        ];
    }

    public function getCategorieLabelAttribute(): string
    {
        return self::categories()[$this->categorie] ?? $this->categorie;
    }

    public function getIconeCategorieAttribute(): string
    {
        return match($this->categorie) {
            'etude' => '📊',
            'publication' => '📰',
            'rapport' => '📋',
            'journal' => '📖',
            'educative' => '🎓',
            default => '📄',
        };
    }
}