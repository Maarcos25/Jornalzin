@extends('layouts.site')

@section('conteudo')
<div class="container">
    <h1>Novo Comentário</h1>

    <form action="{{ route('comments.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Texto</label>
            <textarea name="texto" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>User ID</label>
            <input type="number" name="user_id" class="form-control">
        </div>

        <div class="mb-3">
            <label>Post ID</label>
            <input type="number" name="post_id" class="form-control">
        </div>

        <button class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
