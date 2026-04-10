<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoEditor extends Model
{
    protected $table = 'solicitacoes_editor';
    protected $fillable = ['user_id', 'motivo', 'experiencia', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
