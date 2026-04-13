@extends('layouts.site')

@push('styles')
    <style>
        :root {
            --brand: #4f46e5;
            --brand-light: #818cf8;
            --brand-dark: #3730a3;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --text: #1e293b;
            --muted: #64748b;
            --danger: #ef4444;
            --success: #22c55e;
            --radius: 14px;
            --shadow: 0 2px 12px rgba(0, 0, 0, .07);
        }

        body {
            background: #f1f5f9 !important;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Layout ── */
        .home-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }

        .home-title {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 1.4rem;
            letter-spacing: -.02em;
        }

        /* ── Search ── */
        .search-wrap {
            position: relative;
            margin-bottom: .4rem;
        }

        .search-wrap input {
            width: 100%;
            padding: .85rem 3.5rem .85rem 1.3rem;
            border: 2px solid var(--border);
            border-radius: 50px;
            font-size: 1rem;
            background: var(--surface);
            transition: border-color .2s;
            outline: none;
        }

        .search-wrap input:focus {
            border-color: var(--brand);
        }

        .search-wrap button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: .5rem 1.1rem;
            cursor: pointer;
            font-size: .95rem;
            transition: background .2s;
        }

        .search-wrap button:hover {
            background: var(--brand-dark);
        }

        /* ── Day separator ── */
        .day-block {
            margin-bottom: 2.2rem;
        }

        .day-sep {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin-bottom: 1.2rem;
        }

        .day-sep-badge {
            background: var(--text);
            color: #fff;
            border-radius: 50px;
            padding: .35rem 1.1rem;
            font-size: .85rem;
            font-weight: 700;
            white-space: nowrap;
            text-transform: capitalize;
        }

        .day-sep-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Post card ── */
        .post-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-bottom: 1.1rem;
            overflow: hidden;
            transition: box-shadow .2s;
        }

        .post-card:hover {
            box-shadow: 0 4px 24px rgba(0, 0, 0, .11);
        }

        /* ── Media ── */
        .post-media {
            width: 100%;
        }

        .home-img-single {
            width: 100%;
            max-height: 480px;
            object-fit: cover;
            display: block;
            cursor: zoom-in;
        }

        .home-img-grid {
            display: grid;
            gap: 2px;
        }

        .home-img-grid.two {
            grid-template-columns: 1fr 1fr;
        }

        .home-img-grid.many {
            grid-template-columns: repeat(3, 1fr);
        }

        .home-img-cell {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            cursor: zoom-in;
        }

        .home-img-cell img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .3s;
        }

        .home-img-cell:hover img {
            transform: scale(1.04);
        }

        .home-img-more {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 800;
        }

        .home-video-wrap {
            width: 100%;
            background: #000;
        }

        .home-video-wrap iframe,
        .home-video-wrap video {
            width: 100%;
            display: block;
            aspect-ratio: 16/9;
        }

        /* ── Body ── */
        .post-body {
            padding: 1.1rem 1.2rem .5rem;
        }

        .post-title {
            display: block;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
            text-decoration: none;
            margin-bottom: .4rem;
            line-height: 1.4;
            transition: color .15s;
        }

        .post-title:hover {
            color: var(--brand);
        }

        .post-excerpt {
            font-size: .97rem;
            color: var(--muted);
            line-height: 1.6;
            margin: 0;
        }

        /* ── Poll ── */
        .home-poll {
            margin-top: .7rem;
        }

        .home-poll-label {
            font-size: .88rem;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .home-poll-opt {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem .85rem;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            cursor: pointer;
            font-size: .95rem;
            margin-bottom: .45rem;
            transition: all .15s;
        }

        .home-poll-opt:hover {
            border-color: var(--brand);
            background: #eef2ff;
        }

        .home-poll-opt input {
            accent-color: var(--brand);
        }

        .btn-vote-mini {
            margin-top: .4rem;
            padding: .5rem 1.2rem;
            background: var(--brand);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-vote-mini:hover {
            background: var(--brand-dark);
        }

        .poll-res-row {
            margin-bottom: .5rem;
        }

        .poll-res-label {
            display: flex;
            justify-content: space-between;
            font-size: .88rem;
            color: var(--text);
            margin-bottom: .2rem;
        }

        .poll-res-bg {
            height: 9px;
            background: var(--surface-2);
            border-radius: 99px;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .poll-res-bar {
            height: 100%;
            background: var(--brand-light);
            border-radius: 99px;
            transition: width .5s;
        }

        .poll-res-bar.win {
            background: var(--brand);
        }

        /* ── Footer ── */
        .post-footer {
            display: flex;
            align-items: center;
            padding: .65rem 1.2rem .75rem;
            border-top: 1px solid var(--border);
            gap: .6rem;
            flex-wrap: wrap;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: .35rem;
            font-size: .88rem;
            color: var(--muted);
        }

        .post-meta-sep {
            color: var(--border);
            font-size: .9rem;
        }

        .post-actions {
            margin-left: auto;
            display: flex;
            gap: .5rem;
        }

        .btn-like,
        .btn-comment {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .42rem 1rem;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            background: var(--surface-2);
            color: var(--muted);
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-like:hover {
            border-color: #f43f5e;
            background: #fff1f2;
            color: #f43f5e;
        }

        .btn-like.liked {
            border-color: #f43f5e;
            background: #fff1f2;
            color: #f43f5e;
        }

        .btn-comment:hover {
            border-color: var(--brand);
            background: #eef2ff;
            color: var(--brand);
        }

        .btn-comment.active {
            border-color: var(--brand);
            background: #eef2ff;
            color: var(--brand);
        }

        /* ── Comments panel ── */
        .comments-panel {
            display: none;
            border-top: 1px solid var(--border);
            background: var(--surface-2);
            padding: 1rem 1.2rem;
            animation: slideDown .2s ease;
        }

        .comments-panel.open {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .comment-item {
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            margin-bottom: .8rem;
        }

        .comment-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--brand);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .comment-author {
            font-size: .88rem;
            font-weight: 700;
            color: var(--text);
            margin-right: .4rem;
        }

        .comment-text {
            font-size: .92rem;
            color: var(--muted);
        }

        .comment-form {
            display: flex;
            gap: .5rem;
            margin-top: .7rem;
        }

        .comment-form input {
            flex: 1;
            padding: .52rem .95rem;
            border: 1.5px solid var(--border);
            border-radius: 50px;
            font-size: .92rem;
            outline: none;
            background: var(--surface);
            transition: border-color .2s;
        }

        .comment-form input:focus {
            border-color: var(--brand);
        }

        .comment-form button {
            padding: .52rem 1.2rem;
            border-radius: 50px;
            background: var(--brand);
            color: #fff;
            border: none;
            font-size: .92rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
        }

        .comment-form button:hover {
            background: var(--brand-dark);
        }

        /* ── Lightbox ── */
        #lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .88);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            cursor: zoom-out;
        }

        #lightbox.open {
            display: flex;
        }

        #lightbox img {
            max-width: 92vw;
            max-height: 92vh;
            border-radius: 10px;
            object-fit: contain;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .5);
        }

        /* ── Loading ── */
        #loading {
            padding: 2rem;
            text-align: center;
        }

        @media(max-width: 640px) {
            .home-img-grid.many {
                grid-template-columns: repeat(2, 1fr);
            }

            .post-actions {
                width: 100%;
                justify-content: flex-end;
                margin-left: 0;
            }
        }

        .filtro-link {
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all .2s;
        }

        .filtro-link:hover {
            background: #eef2ff;
            color: var(--brand);
        }

        .filtro-link.active {
            background: var(--brand);
            color: #fff;
        }
        /* ── Masonry Grid ── */
        .masonry-grid {
            column-count: 3;
            column-gap: 1rem;
        }
        .masonry-item {
            break-inside: avoid;
            margin-bottom: 1rem;
            display: block;
            width: 100%;
        }
        .masonry-item.masonry-full {
            column-span: all;
        }
        .masonry-item.masonry-wide {
            column-span: none;
        }
        @media(max-width: 900px) { .masonry-grid { column-count: 2; } }
        @media(max-width: 580px) { .masonry-grid { column-count: 1; } }
    </style>
@endpush

@section('conteudo')
    <div class="home-wrap">

        @php
            $titulos = [
                'views'    => '🔥 Mais Vistos',
                'likes'    => '❤️ Mais Curtidos',
                'recentes' => '🕐 Mais Recentes',
            ];
        @endphp

<h1 class="home-title">{{ request('filtro') ? $titulos[request('filtro')] : '📰 Início' }}</h1>

        {{-- PESQUISA --}}
        <form method="GET" action="/" class="search-wrap">
            <input type="text" name="pesquisa" placeholder="Pesquisar postagens..." value="{{ request('pesquisa') }}">
            <button type="submit">🔍</button>
        </form>

        <div
            style="
    display:flex;
    align-items:center;
    gap:12px;
    margin-top:0;
    margin-bottom:1rem;
    font-size:14px;
">
            <span style="color:var(--muted); font-weight:600; display:flex; align-items:center; gap:.3rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M11 12h2M12 16h0"/>
                </svg>
                Ordenar:
            </span>

            <a href="?filtro=views&pesquisa={{ request('pesquisa') }}"
                class="filtro-link {{ request('filtro') == 'views' ? 'active' : '' }}">
                mais vistos🔥
            </a>

            <a href="?filtro=likes&pesquisa={{ request('pesquisa') }}"
                class="filtro-link {{ request('filtro') == 'likes' ? 'active' : '' }}">
                mais curtidos❤️
            </a>

            <a href="?filtro=recentes&pesquisa={{ request('pesquisa') }}"
                class="filtro-link {{ request('filtro') == 'recentes' ? 'active' : '' }}">
                mais recentes🕐
            </a>
        </div>

        @if (request('pesquisa'))
            <p style="color:var(--muted);font-size:.95rem;margin-bottom:1rem;">
                Resultados para <strong>"{{ request('pesquisa') }}"</strong>
            </p>
        @endif

        {{-- POSTS --}}
        <div id="posts">
            @include('home._posts_por_dia', ['postsPorDia' => $postsPorDia])
        </div>

        {{-- Paginação fallback --}}
        <div class="text-center mt-4 pagination-links">
            {{ $posts->links() }}
        </div>

        {{-- Loading infinito --}}
        <div id="loading" class="d-none">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>

    </div>

    {{-- Lightbox --}}
    <div id="lightbox" onclick="fecharImagem()">
        <img id="lightbox-img" src="" alt="">
    </div>
@endsection

@push('scripts')
    <script>
        // Lightbox
        function abrirImagem(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.add('open');
        }

        function fecharImagem() {
            document.getElementById('lightbox').classList.remove('open');
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') fecharImagem();
        });

        // Toggle comentários
        function toggleComentarios(btn, postId) {
            const panel = document.getElementById('comments-' + postId);
            panel.classList.toggle('open');
            btn.classList.toggle('active');
        }

        // Like AJAX
        function toggleLike(btn, postId) {
            fetch(`/like/${postId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const icon = btn.querySelector('.like-icon');
                    const count = btn.querySelector('.like-count');
                    if (data.liked) {
                        btn.classList.add('liked');
                        icon.textContent = '❤️';
                    } else {
                        btn.classList.remove('liked');
                        icon.textContent = '🤍';
                    }
                    count.textContent = data.total;
                });
        }

        // Infinite scroll
        let page = 1,
            loading = false,
            hasMore = true;
        const postsContainer = document.querySelector('#posts');
        const loadingEl = document.querySelector('#loading');
        document.querySelector('.pagination-links').style.display = 'none';

        window.addEventListener('scroll', () => {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) loadMore();
        });

        function loadMore() {
            if (loading || !hasMore) return;
            loading = true;
            loadingEl.classList.remove('d-none');
            page++;
            const pesquisa = new URLSearchParams(window.location.search).get('pesquisa') || '';

            const filtro = new URLSearchParams(window.location.search).get('filtro') || '';

            const url = `/?page=${page}`
                + (pesquisa ? '&pesquisa=' + encodeURIComponent(pesquisa) : '')
                + (filtro ? '&filtro=' + filtro : '');

            fetch(url)
                .then(r => r.text())
                .then(data => {
                    const parser = new DOMParser();
                    const html = parser.parseFromString(data, 'text/html');
                    const novoPosts = html.querySelector('#posts');
                    if (!novoPosts || novoPosts.innerHTML.trim() === '') {
                        hasMore = false;
                        loadingEl.innerHTML =
                            '<p style="color:var(--muted);font-size:.9rem;">Não há mais postagens.</p>';
                        return;
                    }
                    const ultimoDia = postsContainer.querySelector('[data-dia]:last-of-type')?.dataset.dia;
                    const primeiroDia = novoPosts.querySelector('[data-dia]');
                    if (primeiroDia && primeiroDia.dataset.dia === ultimoDia) {
                        const blocoExistente = postsContainer.querySelector(`[data-dia="${ultimoDia}"] .posts-col`);
                        const blocoNovo = primeiroDia.querySelector('.posts-col');
                        if (blocoExistente && blocoNovo) blocoExistente.innerHTML += blocoNovo.innerHTML;
                        primeiroDia.remove();
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
