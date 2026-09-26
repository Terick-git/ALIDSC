<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partenaire extends Model
{
    protected $fillable = [
        'nom', 'slug', 'type', 'logo', 'site_web', 'email',
        'telephone', 'description', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($partenaire) {
            if (empty($partenaire->slug)) {
                $partenaire->slug = Str::slug($partenaire->nom);
            }
        });
    }
}