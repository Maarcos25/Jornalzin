<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $pesquisa = $request->input('pesquisa');

        $posts = Post::with(['usuario', 'comments.user', 'imagens', 'votos'])
            ->when($pesquisa, function ($query, $pesquisa) {
                return $query->where('titulo', 'like', "%{$pesquisa}%")
                            ->orWhere('texto', 'like', "%{$pesquisa}%");
            })
            ->orderBy('data', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $postsPorDia = $posts->getCollection()
            ->groupBy(fn($post) => Carbon::parse($post->data)->format('Y-m-d'));

        // Destaque: score = visualizacoes + (curtidas * 3)
        // Multiplicador 3 dá mais peso a curtidas (ação ativa) vs visualizações (passiva)
        $destaque = Post::with(['usuario', 'imagens'])
            ->selectRaw('*, (visualizacoes + (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) * 3) as score')
            ->orderByDesc('score')
            ->first();

        $maisVistos = Post::orderBy('visualizacoes', 'desc')->limit(5)->get();

        return view('home', compact('posts', 'postsPorDia', 'maisVistos', 'destaque'));
    }
}
