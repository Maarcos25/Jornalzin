<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required',
            'titulo' => 'required',
            'texto' => 'required',
            'data' => 'required|date',
            'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $dados = $request->all();

        // Upload da imagem
        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $nome = time().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/posts', $nome);
            $dados['imagem'] = 'storage/posts/'.$nome;
        }

        $dados = $request->all();

        if ($request->hasFile('imagem')) {
            $imagem = $request->file('imagem')->store('posts', 'public');
            $dados['imagem'] = $imagem;
        }

        $dados['id_usuario'] = auth()->id();
        $dados['visualizacoes'] = 0;

        Post::create($dados);

        return redirect()->route('posts.index')
            ->with('success','Post criado com sucesso!');

        Post::create([
            'tipo' => $request->tipo,
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'data' => $request->data,
            'visualizacoes' => 0,
            'id_usuario' => auth()->id(),
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'Post criado com sucesso!');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->id_usuario !== auth()->id()) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->id_usuario !== auth()->id()) {
            abort(403);
        }
        $post->update($request->all());

        return redirect()->route('posts.index')->with('success', 'Post atualizado com sucesso!');
    }


    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post excluído com sucesso!');
    }
}

