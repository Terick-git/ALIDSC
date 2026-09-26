<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['album_id', 'fichier', 'legende', 'ordre'];

    protected $casts = ['ordre' => 'integer'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}