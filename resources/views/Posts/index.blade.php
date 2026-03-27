@extends('layouts.site')

@section('conteudo')

<style>
.post-card {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.post-card:hover {
    transform: translateY(-3px);
}

.post-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

.post-text {
    color: #555;
    margin-bottom: 10px;
}

.post-images img {
    width: 100%;
    height: auto;
    border-radius: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.post-images img:hover {
    transform: scale(1.00);
}

.post-video iframe,
.post-video video {
    width: 28%;
    border-radius: 10px;
    margin-top: 10px;
}

.btn {
    border-radius: 8px;
    padding: 5px 10px;
    font-size: 14px;
}
</style>

<div class="container mt-4">

    <h1>Lista de Posts</h1>

    <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">
        Novo Post
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @foreach($posts as $post)

        <div class="post-card">

            <div class="post-title">
                {{ $post->titulo }}
            </div>

            <!-- BOTÕES -->
            <div style="margin-bottom:10px;">
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                    ✏️ Editar
                </a>

                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Tem certeza que deseja excluir?')">
                        🗑️ Excluir
                    </button>
                </form>
            </div>

            <!-- TEXTO -->
            @if($post->tipo == 'texto')
                <p class="post-text">{{ $post->texto }}</p>
            @endif

            <!-- IMAGENS -->
            @if($post->tipo === 'imagem' && $post->imagens->count())
                <div class="post-images">
                    @foreach($post->imagens as $img)
                        <img src="{{ asset('storage/' . $img->caminho) }}"
                             onclick="abrirImagem(this.src)">
                    @endforeach
                </div>
            @endif

            <!-- VIDEO -->
            @if($post->tipo === 'video')
                <div class="post-video">
                    @if(str_contains($post->video, 'youtube') || str_contains($post->video, 'youtu.be'))
                        <iframe height="315"
                            src="{{ str_replace('watch?v=', 'embed/', $post->video) }}"
                            frameborder="0" allowfullscreen>
                        </iframe>
                    @else
                        <video controls>
                            <source src="{{ $post->video }}">
                        </video>
                    @endif
                </div>
            @endif

            <!-- ENQUETE -->
            @if($post->tipo == 'enquete')
                <p>Vote:</p>

                <form method="POST" action="#">
                    @csrf

                    <input type="radio" name="voto_{{ $post->id }}" value="1"> {{ $post->opcao1 }} <br>
                    <input type="radio" name="voto_{{ $post->id }}" value="2"> {{ $post->opcao2 }} <br>
                    <input type="radio" name="voto_{{ $post->id }}" value="3"> {{ $post->opcao3 }} <br>
                    <input type="radio" name="voto_{{ $post->id }}" value="4"> {{ $post->opcao4 }} <br>

                    <button class="btn btn-primary mt-2">Votar</button>
                </form>
            @endif

        </div>

    @endforeach

</div>

<!-- MODAL DE IMAGEM -->
<div id="modalImagem" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.8);
    justify-content:center;
    align-items:center;
">
    <img id="imgModal" style="max-width:90%; max-height:90%;">
</div>

<script>
function abrirImagem(src) {
    document.getElementById('modalImagem').style.display = 'flex';
    document.getElementById('imgModal').src = src;
}

document.getElementById('modalImagem').onclick = function() {
    this.style.display = 'none';
}
</script>

@endsection
