<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostMedia extends Model
{
    protected $table = 'post_imagens';

    protected $fillable = ['post_id', 'caminho', 'ordem'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->caminho);
    }
}
