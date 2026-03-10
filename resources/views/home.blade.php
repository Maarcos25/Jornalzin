@extends('layouts.site')

@section('conteudo')
    <div class="container">

        <h1>Jornalzin 📰</h1>

        <form method="GET" action="/">
            <input type="text" name="pesquisa" placeholder="Pesquisar postagens..." class="form-control mb-3">
        </form>

        <div class="row">

            <div class="col-md-8">

                <h3>Postagens recentes</h3>

                <div id="posts">

                    @foreach ($posts as $post)
                        <div class="card mb-3">
                            <div class="card-body">

                                <h5>{{ $post->titulo }}</h5>

                                <p>{{ $post->texto }}</p>

                                <small>
                                    Autor: {{ $post->usuario->name ?? 'Desconhecido' }}
                                </small>

                                <br>
                                <small>
                                    👁️ {{ $post->visualizacoes }} visualizações
                                </small>

                                <hr>

                                <form method="POST" action="/comments">
                                    @csrf

                                    <input type="hidden" name="post_id" value="{{ $post->id }}">

                                    <input type="text" name="texto" placeholder="Comentar..." class="form-control">

                                    <button class="btn btn-primary btn-sm mt-2">
                                        Enviar
                                    </button>

                                </form>

                            </div>
                        </div>
                    @endforeach


                    <div class="card mb-3">

                        <div class="card-body">

                            <h5>{{ $post->titulo }}</h5>

                            <p>{{ $post->texto }}</p>

                            <small>
                                Autor: {{ $post->usuario->name ?? 'Desconhecido' }}
                            </small>

                            <br>

                            <small>
                                👁️ {{ $post->visualizacoes }} visualizações
                            </small>

                            <br><br>

                            <button class="btn btn-sm btn-outline-primary">👍 Curtir</button>

                            <button class="btn btn-sm btn-outline-secondary">💬 Comentar</button>

                        </div>

                    </div>

                </div>

                <div class="text-center">

                    {{ $posts->links() }}

                </div>

            </div>

            <div class="col-md-4">

                <h3>🔥 Mais vistos</h3>

                <ul class="list-group">

                    @foreach ($maisVistos as $post)
                        <li class="list-group-item">
                            {{ $post->titulo }}
                            <br>
                            <small>{{ $post->visualizacoes }} views</small>
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
