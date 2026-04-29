<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Post;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    // Favoritar / desfavoritar (toggle via AJAX)
    public function toggle(Post $post)
    {
        $user = auth()->user();

        $favorito = Favorito::where('user_id', $user->id)
                            ->where('post_id', $post->id)
                            ->first();

        if ($favorito) {
            $favorito->delete();
            $favoritado = false;
        } else {
            Favorito::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $favoritado = true;
        }

        return response()->json([
            'favoritado' => $favoritado,
            'total'      => $post->favoritos()->count(),
        ]);
    }

    // Página de favoritos do usuário
    public function index()
    {
        $posts = Post::whereHas('favoritos', fn($q) => $q->where('user_id', auth()->id()))
            ->with(['usuario', 'imagens', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->where('aprovado', true)
            ->latest()
            ->paginate(12);

        return view('favoritos.index', compact('posts'));
    }
}
