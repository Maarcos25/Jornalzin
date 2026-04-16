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
        $filtro   = $request->input('filtro');

        $query = Post::with([
            'usuario',
            'imagens',
            'votos',
            'likes',
            'comments' => fn($q) => $q->where('status', 'aprovado')->with('user'),
        ])
        ->withCount(['likes', 'comments'])
        ->where('aprovado', true);

        // Pesquisa
        if ($pesquisa) {
            $query->where(function ($q) use ($pesquisa) {
                $q->where('titulo', 'like', "%{$pesquisa}%")
                  ->orWhere('texto',  'like', "%{$pesquisa}%");
            });
        }

        // Ordenação
        if ($filtro === 'views') {
            $query->orderByDesc('visualizacoes');
        } elseif ($filtro === 'likes') {
            $query->having('likes_count', '>', 0)
                  ->orderByDesc('likes_count');
        } else {
            $query->orderByDesc('created_at');
        }

        $posts = $query->paginate(20);

        // Agrupamento por dia ou "todos" quando filtro ativo
        if ($filtro || $pesquisa) {
            $postsPorDia = collect(['todos' => collect($posts->items())]);
        } else {
            $postsPorDia = $posts->getCollection()
                ->groupBy(fn($post) => Carbon::parse($post->data)->format('Y-m-d'));
        }

        // Post mais relevante (likes*3 + comentarios*2 + visualizacoes)
        $destaqueId = Post::where('aprovado', true)
            ->withCount(['likes', 'comments'])
            ->get()
            ->sortByDesc(fn($p) => ($p->likes_count * 3) + ($p->comments_count * 2) + $p->visualizacoes)
            ->first()
            ?->id;

        return view('home', compact('posts', 'postsPorDia', 'destaqueId'));
    }
}
