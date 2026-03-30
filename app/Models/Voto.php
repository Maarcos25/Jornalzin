<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voto extends Model
{
    protected $fillable = [
        'post_id',
        'id_usuario',
        'opcao',
    ];
}
