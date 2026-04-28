<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversa extends Model
{
    protected $fillable = ['user1_id', 'user2_id', 'ultima_mensagem_at'];

    protected $casts = ['ultima_mensagem_at' => 'datetime'];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    public function outroUsuario()
    {
        $id = auth()->id();
        return $this->user1_id === $id ? $this->user2 : $this->user1;
    }

    public function naoLidas()
    {
        return $this->mensagens()
            ->where('remetente_id', '!=', auth()->id())
            ->where('lida', false)
            ->count();
    }
}
