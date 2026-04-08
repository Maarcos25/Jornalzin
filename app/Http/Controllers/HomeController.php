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
            'comments' => function ($q) use ($status) {
                if ($status) {
                    $q->where('status', $status);
                } else {
                    $q->where('status', 'aprovado');
                }
            },
            'comments.user'
        ]);

        // 🔍 PESQUISA
        if ($pesquisa) {
            $query->where(function ($q) use ($pesquisa) {
                $q->where('titulo', 'like', "%{$pesquisa}%")
                  ->orWhere('texto', 'like', "%{$pesquisa}%");
            });
        }

        // 🔥 FILTROS
        if ($filtro == 'views') {
            $query->orderBy('visualizacoes', 'desc');
        }
        elseif ($filtro == 'likes') {
            $query->whereHas('likes')
                  ->withCount('likes')
                  ->orderBy('likes_count', 'desc');
        }
        else {
            // padrão = mais recentes
            $query->orderBy('created_at', 'desc');
        }

        // 📦 PAGINAÇÃO
        $posts = $query->paginate(20);

        // 🧠 AQUI É O PULO DO GATO
        if ($filtro) {
            $postsPorDia = null; // ❌ NÃO AGRUPA quando tem filtro
        } else {
            $postsPorDia = $posts->getCollection()
                ->groupBy(fn($post) => Carbon::parse($post->data)->format('Y-m-d'));
        }

        // ⭐ DESTAQUE
        $destaque = Post::with(['usuario', 'imagens'])
            ->selectRaw('*, (visualizacoes + (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) * 3) as score')
            ->orderByDesc('score')
            ->first();

        // 👁 MAIS VISTOS
        $maisVistos = Post::orderBy('visualizacoes', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact('posts', 'postsPorDia', 'maisVistos', 'destaque', 'filtro'));
    }
}
