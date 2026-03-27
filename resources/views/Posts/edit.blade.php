@extends('layouts.site')

@section('conteudo')

<div class="container mt-4">

    <h2>Editar Post</h2>

    <form method="POST" action="{{ route('posts.update', $post->id) }}">
        @csrf
        @method('PUT')

        <!-- TÍTULO -->
        <div class="mb-3">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control"
                   value="{{ $post->titulo }}" required>
        </div>

        <!-- TEXTO -->
        @if($post->tipo == 'texto')
            <div class="mb-3">
                <label>Texto</label>
                <textarea name="texto" class="form-control">{{ $post->texto }}</textarea>
            </div>
        @endif

        <!-- DATA -->
        <div class="mb-3">
            <label>Data</label>
            <input type="date" name="data" class="form-control"
                   value="{{ $post->data }}">
        </div>

        <!-- TAMANHO -->
        <div class="mb-3">
            <label>Tamanho</label>
            <select name="tamanho" class="form-control">
                <option value="P" {{ $post->tamanho == 'P' ? 'selected' : '' }}>P</option>
                <option value="M" {{ $post->tamanho == 'M' ? 'selected' : '' }}>M</option>
                <option value="G" {{ $post->tamanho == 'G' ? 'selected' : '' }}>G</option>
                <option value="GG" {{ $post->tamanho == 'GG' ? 'selected' : '' }}>GG</option>
            </select>
        </div>

        <button class="btn btn-success">Salvar</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Voltar</a>

    </form>

</div>

@endsection
