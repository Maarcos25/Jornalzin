@extends('layouts.app')

@section('conteudo')
<div class="container">
    <h1>Editar Comentário</h1>

    <form action="{{ route('comments.update', $comment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Texto</label>
            <textarea name="texto" class="form-control">
                {{ $comment->texto }}
            </textarea>
        </div>

        <button class="btn btn-primary">Atualizar</button>
    </form>
</div>
@endsection
