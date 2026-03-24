@extends('layouts.site')

@section('conteudo')
    <div class="container">

        <h1>início</h1>

        <div class="row">

            <!-- COLUNA POSTS -->
            <div class="col-md-8">

                <!-- PESQUISA -->
                <form method="GET" action="/" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar postagens..."
                            value="{{ request('pesquisa') }}">
                        <button class="btn btn-dark" type="submit">🔍</button>
                    </div>
                </form>

                @if (request('pesquisa'))
                    <h3>🔎 Resultados para "{{ request('pesquisa') }}"</h3>
                @else
                    <h3>Postagens recentes</h3>
                @endif

                <!-- POSTS (FORMATO JORNAL) -->
                <div id="posts" class="row">

                    @foreach ($posts as $post)
                        <div class="col-12 {{ $post->coluna_bootstrap }} mb-4">

                            <div class="card h-100 shadow-sm">

                                <div class="card-body">

                                    <a class="text-decoration-none text-dark" href="{{ route('posts.show', $post->id) }}">

                                        <h4 class="fw-bold">
                                            {{ $post->titulo }}
                                        </h4>

                                        <p class="text-muted">
                                            {{ Str::limit($post->texto, 200) }}
                                        </p>

                                    </a>

                                    <div class="d-flex justify-content-between mb-2">

                                        <small class="text-secondary">
                                            ✍ {{ $post->usuario->name ?? 'Desconhecido' }}
                                        </small>

                                        <small class="text-secondary">
                                            👁 {{ $post->visualizacoes }} visualizações
                                        </small>

                                    </div>

                                    <!-- LIKE -->
                                    <form method="POST" action="{{ route('posts.like', $post->id) }}">
                                        @csrf
                                        <button class="btn btn-outline-primary btn-sm">
                                            👍 Curtir ({{ $post->curtidas ?? 0 }})
                                        </button>
                                    </form>

                                    <!-- COMENTÁRIOS -->
                                    @if ($post->comments && $post->comments->count() > 0)
                                        <div class="mt-3">
                                            @foreach ($post->comments as $comment)
                                                <div style="border-top:1px solid #eee; padding:6px 0; font-size:14px;">
                                                    <strong>{{ $comment->user->name ?? 'Usuário' }}</strong>:
                                                    {{ $comment->texto }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <hr>

                                    <!-- FORM COMENTÁRIO -->
                                    <form method="POST" action="{{ route('comments.store') }}">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">

                                        <input type="text" name="texto" class="form-control"
                                            placeholder="Escreva um comentário...">

                                        <button class="btn btn-primary btn-sm mt-2">
                                            Enviar
                                        </button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- PAGINAÇÃO -->
                <div class="text-center">
                    {{ $posts->links() }}
                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="col-md-4">

                <h3>🔥 Mais vistos</h3>

                <ul class="list-group">
                    @foreach ($maisVistos as $mais)
                        <li class="list-group-item">
                            {{ $mais->titulo }}
                            <br>
                            <small>{{ $mais->visualizacoes }} views</small>
                        </li>
                    @endforeach
                </ul>

            </div>

        </div>

    </div>
@endsection


<script>
    let page = 1;

    window.onscroll = function() {

        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {

            page++;

            fetch('/?page=' + page)
                .then(response => response.text())
                .then(data => {

                    let parser = new DOMParser();
                    let html = parser.parseFromString(data, 'text/html');

                    let novosPosts = html.querySelector('#posts').innerHTML;

                    document.querySelector('#posts').innerHTML += novosPosts;

                });

        }

    };
</script>
