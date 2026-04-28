<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Http\Controllers\NotificacaoController;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private function apenasAdmin()
    {
        if (auth()->user()->tipo !== 'administrador') {
            abort(403, 'Acesso restrito a administradores.');
        }
    }

    public function index()
    {
        $this->apenasAdmin();
        $comments = Comment::with(['user', 'post'])->latest()->get();
        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        $this->apenasAdmin();
        return view('comments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'texto'   => 'required',
            'post_id' => 'required|exists:posts,id'
        ]);

        $comment = Comment::create([
            'texto'   => $request->texto,
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'status'  => 'pendente'
        ]);

        // ── Notificação de comentário ──
        $post = Post::find($request->post_id);
        if ($post) {
            NotificacaoController::criar(
                $post->id_usuario,
                auth()->id(),
                'comentario',
                auth()->user()->nome . ' comentou em "' . $post->titulo . '"',
                route('posts.show', $post->id) . '#comentarios'
            );
        }

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comentário enviado!');
    }

    public function show(Comment $comment)
    {
        $this->apenasAdmin();
        return view('comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        $this->apenasAdmin();
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->apenasAdmin();
        $request->validate(['texto' => 'required']);
        $comment->update(['texto' => $request->texto]);

        return redirect()->route('comments.index')
            ->with('success', 'Comentário atualizado!');
    }

    public function destroy(Comment $comment)
    {
        $this->apenasAdmin();
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'Comentário removido!');
    }

    public function aprovar($id)
    {
        $this->apenasAdmin();
        $comment = Comment::findOrFail($id);
        $comment->status = 'aprovado';
        $comment->save();

        return back()->with('success', 'Comentário aprovado!');
    }

    public function ocultar($id)
    {
        $this->apenasAdmin();
        $comment = Comment::findOrFail($id);
        $comment->status = 'oculto';
        $comment->save();

        return back()->with('success', 'Comentário ocultado!');
    }
}
