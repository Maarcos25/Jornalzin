<?php

namespace App\Http\Controllers;

use App\Models\Denuncia;
use Illuminate\Http\Request;

class DenunciaController extends Controller
{
    private function isAdmin(): bool
    {
        return auth()->check() && auth()->user()->tipo === 'administrador';
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'          => 'required|in:post,comentario',
            'referencia_id' => 'required|integer',
            'motivo'        => 'required|string|max:100',
            'descricao'     => 'nullable|string|max:1000',
        ]);

        Denuncia::create([
            'tipo'          => $request->tipo,
            'referencia_id' => $request->referencia_id,
            'motivo'        => $request->motivo,
            'descricao'     => $request->descricao,
            'user_id'       => auth()->id(),
        ]);

        return back()->with('success', 'Denúncia enviada com sucesso!');
    }

public function index(Request $request)
{
    if (!$this->isAdmin()) abort(403);

    $query = Denuncia::with('usuario')->orderBy('lida')->orderByDesc('created_at');

    if ($request->filtro === 'novas') $query->where('lida', false);
    if ($request->filtro === 'lidas') $query->where('lida', true);

    $denuncias = $query->paginate(20);

    return view('admin.denuncias', compact('denuncias'));
}

    public function marcarLida(Denuncia $denuncia)
    {
        if (!$this->isAdmin()) abort(403);
        $denuncia->update(['lida' => true]);
        return back()->with('success', 'Denúncia marcada como lida.');
    }

    public function destroy(Denuncia $denuncia)
    {
        if (!$this->isAdmin()) abort(403);
        $denuncia->delete();
        return back()->with('success', 'Denúncia removida.');
    }
    public function excluirPost(Denuncia $denuncia)
{
    if (!$this->isAdmin()) abort(403);

    $post = \App\Models\Post::find($denuncia->referencia_id);

    if ($post) {
        // Remove imagens do storage
        foreach ($post->imagens as $img) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($img->caminho);
            $img->delete();
        }
        if ($post->imagem) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('/storage/', '', $post->imagem));
        }
        $post->delete();
    }

    // Remove todas as denúncias relacionadas a este post
    Denuncia::where('tipo', 'post')->where('referencia_id', $denuncia->referencia_id)->delete();

    return back()->with('success', 'Post excluído e denúncias relacionadas removidas. 🗑️');
}

public function excluirComentario(Denuncia $denuncia)
{
    if (!$this->isAdmin()) abort(403);

    $comentario = \App\Models\Comment::find($denuncia->referencia_id);
    if ($comentario) $comentario->delete();

    // Remove todas as denúncias relacionadas a este comentário
    Denuncia::where('tipo', 'comentario')->where('referencia_id', $denuncia->referencia_id)->delete();

    return back()->with('success', 'Comentário excluído e denúncias relacionadas removidas. 🗑️');
}
}
