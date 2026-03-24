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

        $posts = Post::with(['usuario', 'comments.user'])
            ->when($pesquisa, function ($query, $pesquisa) {
                return $query->where('titulo', 'like', "%{$pesquisa}%")
                            ->orWhere('texto', 'like', "%{$pesquisa}%");
            })
            ->orderBy('data', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Aumentado para pegar mais posts por página e garantir dias completos

        // Agrupa os posts por data (Y-m-d) mantendo a paginação do Laravel
        $postsPorDia = $posts->getCollection()
            ->groupBy(fn($post) => Carbon::parse($post->data)->format('Y-m-d'));

        $maisVistos = Post::orderBy('visualizacoes', 'desc')->limit(5)->get();

        return view('home', compact('posts', 'postsPorDia', 'maisVistos'));
    }
}
