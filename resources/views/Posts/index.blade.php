@extends('layouts.app')

@section('content')
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
    <tr>
        <td>{{ $post->id }}</td>
        <td>{{ $post->titulo }}</td>
        <td>{{ $post->tipo }}</td>
        <td>{{ $post->data }}</td>
        <td>
            <a href="{{ route('posts.show', $post) }}">Ver</a>
            <a href="{{ route('posts.edit', $post) }}">Editar</a>

            <form action="{{ route('posts.destroy', $post) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Excluir</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
