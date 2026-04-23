<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'password',
        'ra',
        'telefone',
        'data_nascimento',
        'tipo',
        'avatar'

    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'data_nascimento' => 'date',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'id_usuario');
    }
    public function seguindo()
{
    return $this->belongsToMany(User::class, 'seguidores', 'seguidor_id', 'seguido_id');
}

public function seguidores()
{
    return $this->belongsToMany(User::class, 'seguidores', 'seguido_id', 'seguidor_id');
}

}
