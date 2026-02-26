@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Comentários</h1>

    <a href="{{ route('comments.create') }}" class="btn btn-primary">
        Novo Comentário
    </a>

    @if(session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div> 
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Texto</th>
                <th>Usuário</th>
                <th>Post</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->texto }}</td>
                    <td>{{ $comment->user->name ?? '' }}</td>
                    <td>{{ $comment->post->title ?? '' }}</td>
                    <td>{{ $comment->data }}</td>
                    <td>
                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
