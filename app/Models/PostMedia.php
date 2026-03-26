<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImagem extends Model
{
    protected $fillable = ['post_id', 'caminho', 'ordem'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Accessor: retorna URL pública completa
    public function getUrlAttribute(): string
    {
        return Storage::url($this->caminho);
    }
}
