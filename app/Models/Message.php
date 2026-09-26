<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'email', 'telephone', 'sujet', 'contenu', 'lu', 'lu_le'];

    protected $casts = [
        'lu' => 'boolean',
        'lu_le' => 'datetime',
    ];
}