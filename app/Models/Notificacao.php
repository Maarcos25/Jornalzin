<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = [
        'user_id',
        'ator_id',
        'tipo',
        'mensagem',
        'link',
        'lida',
    ];

    public function ator()
    {
        return $this->belongsTo(User::class, 'ator_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Ícone por tipo
    public function getIconeAttribute(): string
    {
        return match($this->tipo) {
            'like'       => '❤️',
            'comentario' => '💬',
            'seguidor'   => '👤',
            'mensagem'   => '✉️',
            'post'       => '📰',
            default      => '🔔',
        };
    }
}
