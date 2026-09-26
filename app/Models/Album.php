<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    protected $fillable = [
        'titre', 'slug', 'description', 'couverture',
        'date_evenement', 'lieu', 'ordre', 'publie', 'user_id',
    ];

    protected $casts = [
        'date_evenement' => 'date',
        'publie' => 'boolean',
        'ordre' => 'integer',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class)->orderBy('ordre');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->titre) . '-' . date('Ymd');
            }
        });
    }

    public function getNombrePhotosAttribute(): int
    {
        return $this->photos()->count();
    }
}