<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('imagens')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // ── Regras base ──────────────────────────────────────────────────
        $rules = [
            'tipo'    => 'required|in:texto,imagem,video,enquete',
            'titulo'  => 'required|string|max:255',
            'data'    => 'required|date',
            'tamanho' => 'nullable|in:P,M,G,GG',
        ];

        // Regras dinâmicas por tipo
        switch ($request->tipo) {
            case 'texto':
                $rules['texto'] = 'required|string';
                break;

            case 'imagem':
                $rules['imagens']   = 'required|array|min:1|max:10';
                $rules['imagens.*'] = 'image|mimes:jpg,jpeg,png,webp|max:5120';
                break;

            case 'video':
                $hasUrl  = $request->filled('video_url');
                $hasFile = $request->hasFile('video_file');
                if (!$hasUrl && !$hasFile) {
                    return back()->withErrors(['video' => 'Informe um link ou envie um arquivo de vídeo.'])->withInput();
                }
                if ($hasUrl)  $rules['video_url']  = 'nullable|url';
                if ($hasFile) $rules['video_file'] = 'nullable|file|mimes:mp4,webm,ogg|max:102400'; // 100 MB
                break;

            case 'enquete':
                $rules['opcoes']   = 'required|array|min:2|max:8';
                $rules['opcoes.*'] = 'required|string|max:200';
                break;
        }

        $validated = $request->validate($rules);

        // ── Monta dados do post ──────────────────────────────────────────
        $dados = [
            'tipo'        => $request->tipo,
            'titulo'      => $request->titulo,
            'texto'       => $request->texto,
            'data'        => $request->data,
            'tamanho'     => $request->tamanho ?? 'M',
            'visualizacoes' => 0,
            'id_usuario'  => auth()->id(),
        ];

        // ── Vídeo ────────────────────────────────────────────────────────
        if ($request->tipo === 'video') {
            if ($request->hasFile('video_file')) {
                $path = $request->file('video_file')->store('posts/videos', 'public');
                $dados['video'] = Storage::url($path);
            } else {
                $dados['video'] = $request->video_url;
            }
        }

        // ── Enquete ──────────────────────────────────────────────────────
        if ($request->tipo === 'enquete') {
            $opcoes = array_values(array_filter($request->opcoes));
            foreach ($opcoes as $i => $opcao) {
                $dados['opcao' . ($i + 1)] = $opcao; // opcao1…opcao8
            }
            // Campos extras de enquete (não obrigatórios no modelo base)
            // Se quiser persistir: adicione colunas enquete_fim e multipla_escolha
        }

        // ── Cria o post ──────────────────────────────────────────────────
        $post = Post::create($dados);

        // ── Múltiplas imagens ────────────────────────────────────────────
        if ($request->tipo === 'imagem' && $request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $ordem => $file) {
                $path = $file->store('posts/imagens', 'public');

                // Salva em tabela relacionada (PostImagem)
                $post->imagens()->create([
                    'caminho' => $path,
                    'ordem'   => $ordem,
                ]);
            }

            // Mantém retrocompatibilidade: salva a 1ª imagem na coluna legada
            $post->update(['imagem' => Storage::url($post->imagens()->orderBy('ordem')->first()->caminho)]);
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post criado com sucesso! 🎉');
    }

    public function show(Post $post)
    {
        $post->increment('visualizacoes');
        return view('posts.show', compact('post'));
    }

    // $this->authorize('update', $post);
// $this->authorize('delete', $post);
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

        $post->update($request->only(['titulo', 'texto', 'data', 'tamanho']));

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post atualizado com sucesso!');
    }

    public function destroy(Post $post)
    {
        if ($post->id_usuario !== auth()->id()) {
            abort(403);
        }

        foreach ($post->imagens as $img) {
            Storage::disk('public')->delete($img->caminho);
            $img->delete();
        }

        if ($post->imagem) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->imagem));
        }

        if ($post->video) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->video));
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post excluído com sucesso!');
    }

    public function deleteVideo(Post $post)
    {
        if ($post->id_usuario !== auth()->id()) {
            abort(403);
        }

        if ($post->video) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->video));
            $post->video = null;
            $post->save();
        }

        return back()->with('success', 'Vídeo removido!');
    }

    public function removerMidia(Post $post)
    {
        if ($post->id_usuario !== auth()->id()) {
            abort(403);
        }

        if ($post->video) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $post->video));
            $post->video = null;
        }

        foreach ($post->imagens as $img) {
            Storage::disk('public')->delete($img->caminho);
            $img->delete();
        }

        $post->save();

        return back()->with('success', 'Arquivo removido!');
    }
