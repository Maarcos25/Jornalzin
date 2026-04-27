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

    /* ── Relacionamentos ── */

    public function posts()
    {
        return $this->hasMany(Post::class, 'id_usuario');
    }

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class, 'user_id');
    }

    // Usuários que este usuário segue
    public function seguindo()
    {
        return $this->belongsToMany(User::class, 'seguidores', 'seguidor_id', 'seguido_id');
    }

    // Usuários que seguem este usuário
    public function seguidores()
    {
        return $this->belongsToMany(User::class, 'seguidores', 'seguido_id', 'seguidor_id');
    }

    // Verifica se está seguindo um usuário específico
    public function seguindo_usuario(int $userId): bool
    {
        return $this->seguindo()->where('seguido_id', $userId)->exists();
    }
}