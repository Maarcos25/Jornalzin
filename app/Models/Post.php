<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'tipo',
        'titulo',
        'texto',
        'imagem',
        'video',
        'opcao1',
        'opcao2',
        'opcao3',
        'opcao4',
        'data',
        'visualizacoes',
        'id_usuario'
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

}

