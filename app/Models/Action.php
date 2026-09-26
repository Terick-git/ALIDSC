<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'categorie', 'extrait', 'contenu', 'image', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($action) {
            if (empty($action->slug)) {
                $action->slug = Str::slug($action->titre);
            }
        });
    }
}
