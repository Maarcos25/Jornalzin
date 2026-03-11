@extends('layouts.site')

@section('conteudo')
<h1>Lista de Posts</h1>

<a href="{{ route('posts.create') }}">Novo Post</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Tipo</th>
        <th>Data</th>
        <th>Ações</th>
    </tr>

    @foreach($posts as $post)

    <div class="card mb-3">

    <div class="card-body">

    <h4>{{ $post->titulo }}</h4>

    @if($post->tipo == 'texto')

    <p>{{ $post->texto }}</p>

    @endif


    @if($post->tipo == 'imagem')

    <img src="{{ asset('storage/'.$post->imagem) }}" width="300">

    @endif


    @if($post->tipo == 'video')

    <iframe width="400" height="200"
    src="{{ $post->video }}"
    frameborder="0"></iframe>

    @endif


    @if($post->tipo == 'enquete')

    <p>Vote:</p>

    <form>

    <input type="radio"> {{ $post->opcao1 }} <br>
    <input type="radio"> {{ $post->opcao2 }} <br>
    <input type="radio"> {{ $post->opcao3 }} <br>
    <input type="radio"> {{ $post->opcao4 }} <br>

    <button class="btn btn-primary mt-2">Votar</button>

    </form>

    @endif

    </div>
    </div>

    @endforeach
</table>
@endsection
