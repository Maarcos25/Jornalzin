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
        $status   = $request->input('status');
        $filtro   = $request->input('filtro');

        $query = Post::with([
            'usuario',
            'imagens',
            'votos',
            'likes',
            'comments' => function ($q) use ($status) {
                if ($status) {
                    $q->where('status', $status);
                } else {
                    $q->where('status', 'aprovado');
                }
            },
            'comments.user'
        ])->where('aprovado', true);

        // 🔍 PESQUISA
        if ($pesquisa) {
            $query->where(function ($q) use ($pesquisa) {
                $q->where('titulo', 'like', "%{$pesquisa}%")
                  ->orWhere('texto', 'like', "%{$pesquisa}%");
            });
        }

        // 🔥 FILTRO
        if ($filtro == 'views') {
            $query->orderBy('visualizacoes', 'desc');
        } elseif ($filtro == 'likes') {
            $query->withCount('likes')
                  ->orderBy('likes_count', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(20);

        // 📅 AGRUPAR
        if ($filtro) {
            $postsPorDia = collect(['todos' => $posts->items()]);
        } else {
            $postsPorDia = $posts->getCollection()
                ->groupBy(fn($post) => Carbon::parse($post->data)->format('Y-m-d'));
        }

        // ⭐ POST MAIS RELEVANTE
        $destaque = Post::where('aprovado', true)
            ->selectRaw('posts.*, (visualizacoes + (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) * 3) as score')
            ->orderByDesc('score')
            ->first();

        // 👁 MAIS VISTO
        $maisVisto = Post::where('aprovado', true)
            ->orderByDesc('visualizacoes')
            ->first();

        // ❤️ MAIS CURTIDO
        $maisCurtido = Post::where('aprovado', true)
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->first();

        // 💬 MAIS COMENTADO (NOVO)
        $maisComentado = Post::where('aprovado', true)
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->first();

        return view('home', compact(
            'posts',
            'postsPorDia',
            'maisVisto',
            'maisCurtido',
            'maisComentado',
            'destaque'
        ));
    }
}
