<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Evenement extends Model
{
    protected $fillable = [
        'titre', 'slug', 'extrait', 'contenu', 'image',
        'date_debut', 'date_fin', 'lieu', 'ville', 'intervenants',
        'inscription_requise', 'lien_inscription', 'statut', 'user_id',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'inscription_requise' => 'boolean',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($evenement) {
            if (empty($evenement->slug)) {
                $evenement->slug = Str::slug($evenement->titre);
            }
        });
    }

    // Événement à venir ?
    public function getEstAVenirAttribute(): bool
    {
        return $this->date_debut->isFuture();
    }

    // Badge couleur selon statut
    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'publie' => 'Publié',
            'annule' => 'Annulé',
            default => 'Brouillon',
        };
    }
}