<?php

namespace App\Http\Controllers;

use App\Models\Conversa;
use App\Models\Mensagem;
use App\Models\User;
use App\Http\Controllers\NotificacaoController;
use Illuminate\Http\Request;

class DMController extends Controller
{
    public function index()
    {
        $conversas = Conversa::where('user1_id', auth()->id())
            ->orWhere('user2_id', auth()->id())
            ->with(['user1', 'user2', 'mensagens' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('ultima_mensagem_at')
            ->get();

        return view('dm.index', compact('conversas'));
    }

    public function abrir(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('dm.index');
        }

        $ids = collect([auth()->id(), $user->id])->sort()->values();

        $conversa = Conversa::firstOrCreate([
            'user1_id' => $ids[0],
            'user2_id' => $ids[1],
        ]);

        return redirect()->route('dm.conversa', $conversa->id);
    }

    public function conversa(Conversa $conversa)
    {
        if ($conversa->user1_id !== auth()->id() && $conversa->user2_id !== auth()->id()) {
            abort(403);
        }

        $conversa->mensagens()
            ->where('remetente_id', '!=', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);

        $mensagens = $conversa->mensagens()->with('remetente')->orderBy('created_at')->get();
        $outro = $conversa->outroUsuario();

        return view('dm.conversa', compact('conversa', 'mensagens', 'outro'));
    }

    public function enviar(Request $request, Conversa $conversa)
    {
        if ($conversa->user1_id !== auth()->id() && $conversa->user2_id !== auth()->id()) {
            abort(403);
        }

        $request->validate(['texto' => 'required|string|max:1000']);

        Mensagem::create([
            'conversa_id'  => $conversa->id,
            'remetente_id' => auth()->id(),
            'texto'        => $request->texto,
            'lida'         => false,
        ]);

        $conversa->update(['ultima_mensagem_at' => now()]);

        // ── Notificação de mensagem ──
        NotificacaoController::criar(
            $conversa->outroUsuario()->id,
            auth()->id(),
            'mensagem',
            auth()->user()->nome . ' te enviou uma mensagem',
            route('dm.conversa', $conversa->id)
        );

        return back();
    }

    public static function totalNaoLidas()
    {
        if (!auth()->check()) return 0;

        return Mensagem::whereHas('conversa', function($q) {
            $q->where('user1_id', auth()->id())
              ->orWhere('user2_id', auth()->id());
        })
        ->where('remetente_id', '!=', auth()->id())
        ->where('lida', false)
        ->count();
    }
    public function enviarPost(Request $request)
{
    $request->validate(['user_id' => 'required|exists:users,id', 'texto' => 'required|string']);
    $ids = collect([auth()->id(), $request->user_id])->sort()->values();
    $conversa = \App\Models\Conversa::firstOrCreate(['user1_id' => $ids[0], 'user2_id' => $ids[1]]);
    \App\Models\Mensagem::create([
        'conversa_id'  => $conversa->id,
        'remetente_id' => auth()->id(),
        'texto'        => $request->texto,
        'lida'         => false,
    ]);
    $conversa->update(['ultima_mensagem_at' => now()]);
    return response()->json(['ok' => true]);
}
}
