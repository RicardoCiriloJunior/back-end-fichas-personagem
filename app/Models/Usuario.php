<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'senha',
        'nome',
        'ficha'
    ];

    protected $casts = [
        'ficha' => 'array'
    ];
    protected $hidden = ['senha'];
}
