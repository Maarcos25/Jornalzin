@extends('layouts.site')

@section('conteudo')
<div class="container">

    <h1>início</h1>

    <div class="row">
        <div class="col-md-12">

            <!-- PESQUISA -->
            <form method="GET" action="/" class="mb-4">
                <div class="input-group">
                    <input type="text" name="pesquisa" class="form-control"
                        placeholder="Pesquisar postagens..."
                        value="{{ request('pesquisa') }}">
                    <button class="btn btn-dark" type="submit">🔍</button>
                </div>
            </form>

            @if (request('pesquisa'))
                <h3>🔎 Resultados para "{{ request('pesquisa') }}"</h3>
            @endif

            <!-- POSTS AGRUPADOS POR DIA -->
            <div id="posts">
                @include('home._posts_por_dia', ['postsPorDia' => $postsPorDia])
            </div>

            <!-- PAGINAÇÃO (fallback sem JS) -->
            <div class="text-center mt-4 pagination-links">
                {{ $posts->links() }}
            </div>

            <!-- Indicador de carregamento -->
            <div id="loading" class="text-center py-4 d-none">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let page = 1;
    let loading = false;
    let hasMore = true;

    const postsContainer = document.querySelector('#posts');
    const loadingEl = document.querySelector('#loading');
    const paginationLinks = document.querySelector('.pagination-links');

    // Esconde a paginação padrão (usamos infinite scroll)
    if (paginationLinks) paginationLinks.style.display = 'none';

    window.addEventListener('scroll', () => {
        // Dispara quando está a 300px do final da página
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
            loadMore();
        }
    });

    function loadMore() {
        if (loading || !hasMore) return;

        loading = true;
        loadingEl.classList.remove('d-none');

        page++;

        const pesquisa = new URLSearchParams(window.location.search).get('pesquisa') || '';
        const url = `/?page=${page}${pesquisa ? '&pesquisa=' + encodeURIComponent(pesquisa) : ''}`;

        fetch(url)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const html = parser.parseFromString(data, 'text/html');
                const novoPosts = html.querySelector('#posts');

                if (!novoPosts || novoPosts.innerHTML.trim() === '') {
                    hasMore = false;
                    loadingEl.innerHTML = '<p class="text-muted">Não há mais postagens.</p>';
                    return;
                }

                // Pega o último separador de dia já exibido
                const ultimoDiaSeparador = postsContainer.querySelector('[data-dia]:last-of-type');
                const ultimoDia = ultimoDiaSeparador?.dataset.dia;

                // Verifica se o primeiro grupo do novo conteúdo é o mesmo dia do último exibido
                const primeiroDiaNovo = novoPosts.querySelector('[data-dia]');
                if (primeiroDiaNovo && primeiroDiaNovo.dataset.dia === ultimoDia) {
                    // Mesmo dia: mescla os posts sem duplicar o cabeçalho
                    const blocoExistente = postsContainer.querySelector(`[data-dia="${ultimoDia}"] .row`);
                    const blocoNovo = primeiroDiaNovo.querySelector('.row');
                    if (blocoExistente && blocoNovo) {
                        blocoExistente.innerHTML += blocoNovo.innerHTML;
                    }
                    // Remove o primeiro grupo do HTML novo (já mesclamos)
                    primeiroDiaNovo.remove();
                }

                postsContainer.innerHTML += novoPosts.innerHTML;
            })
            .catch(() => {
                hasMore = false;
            })
            .finally(() => {
                loading = false;
                loadingEl.classList.add('d-none');
            });
    }
</script>
@endpush
