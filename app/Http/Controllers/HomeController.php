<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $pesquisa = $request->input('pesquisa');

        $posts = Post::with(['usuario', 'comments.user']) // 🔥 AQUI
            ->when($pesquisa, function ($query, $pesquisa) {
                return $query->where('titulo', 'like', "%{$pesquisa}%")
                            ->orWhere('texto', 'like', "%{$pesquisa}%");
            })
            ->orderBy('data', 'desc')
            ->paginate(5);

        $maisVistos = Post::orderBy('visualizacoes', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact('posts', 'maisVistos'));
        }
    }
