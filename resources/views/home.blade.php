@extends('layouts.site')

@section('conteudo')
<div class="container">

    <h1>Jornalzin 📰</h1>

    <div class="row">

        <!-- COLUNA POSTS -->
        <div class="col-md-8">

            <!-- PESQUISA -->
            <form method="GET" action="/" class="mb-4">
                <div class="input-group">

                    <input
                        type="text"
                        name="pesquisa"
                        class="form-control"
                        placeholder="Pesquisar postagens..."
                        value="{{ request('pesquisa') }}"
                    >

                    <button class="btn btn-dark" type="submit">
                        🔍
                    </button>

                </div>
            </form>


            @if(request('pesquisa'))
                <h3>🔎 Resultados para "{{ request('pesquisa') }}"</h3>
            @else
                <h3>Postagens recentes</h3>
            @endif


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

                    </div>
                </div>

                @endforeach

            </div>

            <div class="text-center">
                {{ $posts->links() }}
            </div>

        </div>


        <!-- COLUNA MAIS VISTOS -->
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
