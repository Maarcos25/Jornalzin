@extends('layouts.site')

@section('conteudo')

<div class="container mt-4">

    <h2>Editar Post</h2>

    <form method="POST" action="{{ route('posts.update', $post->id) }}">
        @csrf
        @method('PUT')

        <!-- TIPO -->
        <p><strong>Tipo:</strong> {{ $post->tipo }}</p>

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

        <!-- IMAGENS (MOSTRAR) -->
        @if($post->tipo === 'imagem' && $post->imagens->count())
            <div class="mb-3">
                <label>Imagens atuais:</label><br>
                @foreach($post->imagens as $img)
                    <img src="{{ asset('storage/' . $img->caminho) }}"
                         style="width:120px; margin:5px; border-radius:8px;">
                @endforeach
            </div>
        @endif

        <!-- VIDEO -->
        @if($post->tipo === 'video')
            <div class="mb-3">
                <label>Vídeo atual:</label><br>

                @if(str_contains($post->video, 'youtube'))
                    <iframe width="300"
                        src="{{ str_replace('watch?v=', 'embed/', $post->video) }}">
                    </iframe>
                @else
                    <video width="300" controls>
                        <source src="{{ $post->video }}">
                    </video>
                @endif
            </div>
        @endif

        <!-- ENQUETE -->
        @if($post->tipo == 'enquete')
            <div class="mb-3">
                <label>Opções:</label>

                <input type="text" name="opcao1" class="form-control mb-2" value="{{ $post->opcao1 }}">
                <input type="text" name="opcao2" class="form-control mb-2" value="{{ $post->opcao2 }}">
                <input type="text" name="opcao3" class="form-control mb-2" value="{{ $post->opcao3 }}">
                <input type="text" name="opcao4" class="form-control mb-2" value="{{ $post->opcao4 }}">
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
