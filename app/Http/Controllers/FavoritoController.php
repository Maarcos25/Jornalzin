<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Post;
use Illuminate\Http\Request;

class FavoritoController extends Controller
{
    public function index()
    {
        $posts = Post::whereHas('favoritos', function($q) {
            $q->where('user_id', auth()->id());
        })
        ->with(['usuario', 'imagens', 'likes', 'comments'])
        ->withCount(['likes', 'comments'])
        ->orderByDesc('created_at')
        ->paginate(12);

        return view('favoritos.index', compact('posts'));
    }

    public function toggle(Post $post)
    {
        $favorito = Favorito::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->first();

        if ($favorito) {
            $favorito->delete();
            $favoritado = false;
        } else {
            Favorito::create([
                'user_id' => auth()->id(),
                'post_id' => $post->id,
            ]);
            $favoritado = true;
        }

        return response()->json(['favoritado' => $favoritado]);
    }
}
