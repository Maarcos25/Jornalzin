<?php
namespace App\Http\Controllers;

use App\Models\Comment;
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
        $this->apenasAdmin();
        $request->validate([
            'texto' => 'required',
            'post_id' => 'required|exists:posts,id'
        ]);

        $comment = Comment::create([
            'texto' => $request->texto,
            'user_id' => auth()->id(),
            'post_id' => $request->post_id
        ]);

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comentário criado!');
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
            ->with('success', 'Comentário atualizado com sucesso!');
    }

    public function destroy(Comment $comment)
    {
        $this->apenasAdmin();
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'Comentário removido com sucesso!');
    }
}
