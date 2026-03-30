<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PostMedia;

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
        'tamanho',
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

    public function getColunaBootstrapAttribute()
    {
        return match ($this->tamanho) {
            'P' => 'col-md-3',
            'M' => 'col-md-4',
            'G' => 'col-md-6',
            'GG' => 'col-md-12',
            default => 'col-md-4',
        };
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }

    public function getCurtidasAttribute()
    {
        return $this->likes()->count();
    }

    // Relacionamento: um post tem muitas imagens
public function imagens()
{
    return $this->hasMany(PostMedia::class, 'post_id')->orderBy('ordem');
}
    // Retorna array de URLs de todas as imagens
    public function getImagensUrlAttribute(): array
    {
        return $this->imagens->map(fn($i) => $i->url)->toArray();
    }
    public function votos()
{
    return $this->hasMany(Voto::class, 'post_id');
}
}
