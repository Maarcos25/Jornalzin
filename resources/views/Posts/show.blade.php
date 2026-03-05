@extends('layouts.site')

@section('conteudo')
<h1>{{ $post->titulo }}</h1>

<p>Tipo: {{ $post->tipo }}</p>
<p>Texto: {{ $post->texto }}</p>
<p>Data: {{ $post->data }}</p>
<p>Visualizações: {{ $post->visualizacoes }}</p>

<a href="{{ route('posts.index') }}">Voltar</a>
<hr>
<h3>Comentários</h3>

@foreach($post->comentarios as $comentario)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:5px;">
        <strong>{{ $comentario->user->name }}</strong>
        <p>{{ $comentario->texto }}</p>
    </div>

@endforeach
@endsection
@auth
<hr>
<h4>Adicionar Comentário</h4>

<form action="{{ route('comentarios.store', $post) }}" method="POST">
    @csrf
    <textarea name="texto" rows="3"></textarea>
    <br><br>
    <button type="submit">Comentar</button>
</form>
@endauth
