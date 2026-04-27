<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    protected $fillable = [
        'tipo',
        'referencia_id',
        'motivo',
        'descricao',
        'user_id',
        'lida',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}