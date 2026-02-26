<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['user', 'post'])->latest()->get();
        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        return view('comments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'texto' => 'required',
            'user_id' => 'required',
            'post_id' => 'required',
        ]);

        Comment::create([
            'texto' => $request->texto,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'data' => now()
        ]);

        return redirect()->route('comments.index')
            ->with('success', 'Comentário criado com sucesso!');
    }

    public function show(Comment $comment)
    {
        return view('comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'texto' => 'required',
        ]);

        $comment->update([
            'texto' => $request->texto,
        ]);

        return redirect()->route('comments.index')
            ->with('success', 'Comentário atualizado com sucesso!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'Comentário removido com sucesso!');
    }
}
