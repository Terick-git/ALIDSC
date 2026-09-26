<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Actualite extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'extrait', 'contenu', 'image',
        'publie', 'interne', 'publie_le', 'user_id',
    ];

    protected $casts = [
        'publie' => 'boolean',
        'interne' => 'boolean',
        'publie_le' => 'datetime',
    ];

    // Relation : une actualité appartient à un user (auteur)
    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Génère automatiquement le slug depuis le titre
    protected static function booted()
    {
        static::creating(function ($actualite) {
            if (empty($actualite->slug)) {
                $actualite->slug = Str::slug($actualite->titre);
            }
        });
    }
}