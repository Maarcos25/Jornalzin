<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'password',
        'ra',
        'telefone',
        'data_nascimento',
        'tipo',
        'avatar',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'data_nascimento'   => 'date',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'id_usuario');
    }
    public function favoritos()
{
    return $this->hasMany(\App\Models\Favorito::class);
}

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'user_id');
    }

    public function seguindo()
    {
        return $this->belongsToMany(User::class, 'seguidores', 'seguidor_id', 'seguido_id');
    }

    public function seguidores()
    {
        return $this->belongsToMany(User::class, 'seguidores', 'seguido_id', 'seguidor_id');
    }

    public function seguindo_usuario(int $userId): bool
    {
        return $this->seguindo()->where('seguido_id', $userId)->exists();
    }

    public function conversas()
    {
        return \App\Models\Conversa::where('user1_id', $this->id)
            ->orWhere('user2_id', $this->id)
            ->with(['user1', 'user2', 'mensagens'])
            ->orderByDesc('ultima_mensagem_at')
            ->get();
    }
}
