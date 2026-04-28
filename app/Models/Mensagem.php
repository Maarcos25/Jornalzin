<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = ['conversa_id', 'remetente_id', 'texto', 'lida'];

    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id');
    }

    public function conversa()
    {
        return $this->belongsTo(Conversa::class);
    }
}
