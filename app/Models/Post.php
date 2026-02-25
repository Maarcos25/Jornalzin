<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'tipo',
        'titulo',
        'conteudo',
        'arquivo',
        'data_postagem',
        'visualizacoes',
        'user_id'
    ];

    protected $casts = [
        'data_postagem' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
