<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function index(Request $request)
    {
        $tipo  = $request->input('tipo');
        $query = Notificacao::where('user_id', auth()->id())
            ->with('ator')
            ->orderByDesc('created_at');

        if ($tipo && $tipo !== 'todas') {
            $query->where('tipo', $tipo);
        }

        $notificacoes = $query->paginate(20);

        // Marca todas como lidas ao abrir
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);

        return view('notificacoes.index', compact('notificacoes', 'tipo'));
    }

    public function marcarTodasLidas()
    {
        Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);

        return back();
    }

    public static function criar(int $userId, int $atorId, string $tipo, string $mensagem, ?string $link = null): void
    {
        if ($userId === $atorId) return;

        if (in_array($tipo, ['like', 'seguidor'])) {
            $existe = Notificacao::where('user_id', $userId)
                ->where('ator_id', $atorId)
                ->where('tipo', $tipo)
                ->where('link', $link)
                ->exists();
            if ($existe) return;
        }

        Notificacao::create([
            'user_id'  => $userId,
            'ator_id'  => $atorId,
            'tipo'     => $tipo,
            'mensagem' => $mensagem,
            'link'     => $link,
            'lida'     => false,
        ]);
    }
}
