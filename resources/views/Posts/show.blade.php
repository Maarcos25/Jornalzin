@extends('layouts.app')

@section('conteudo')
<h1>{{ $post->titulo }}</h1>

<p>Tipo: {{ $post->tipo }}</p>
<p>Texto: {{ $post->texto }}</p>
<p>Data: {{ $post->data }}</p>
<p>Visualizações: {{ $post->visualizacoes }}</p>

<a href="{{ route('posts.index') }}">Voltar</a>
@endsection
