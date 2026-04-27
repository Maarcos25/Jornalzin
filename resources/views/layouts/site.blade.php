<!DOCTYPE html>
<html lang="pt-br" id="html-root">
<head>
    <link href="https://fonts.googleapis.com/css2?family=UnifrakturMaguntia&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jornalzin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg:        #f1f5f9;
            --surface:   #ffffff;
            --surface-2: #f8fafc;
            --border:    #e2e8f0;
            --text:      #1e293b;
            --muted:     #64748b;
            --brand:     #6366f1;
            --brand-light:#818cf8;
            --brand-dark: #4f46e5;
            --danger:    #ef4444;
            --nav-bg:    #1e293b;
            --nav-border:#334155;
        }
        html.dark {
            --bg:        #0f172a;
            --surface:   #1e293b;
            --surface-2: #1a2744;
            --border:    #334155;
            --text:      #e2e8f0;
            --muted:     #94a3b8;
            --brand:     #818cf8;
            --brand-light:#a5b4fc;
            --brand-dark: #6366f1;
            --danger:    #f87171;
            --nav-bg:    #0b1120;
            --nav-border:#1e293b;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            transition: background-color .3s ease, border-color .3s ease, color .2s ease, box-shadow .3s ease;
        }

        html, body { height: 100%; }
        body {
            background-color: var(--bg) !important;
            color: var(--text) !important;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .page-content { flex: 1; }

        /* ── Navbar ── */
        .navbar-jornalzin {
            background: var(--nav-bg) !important;
            border-bottom: 1px solid var(--nav-border);
            box-shadow: 0 1px 8px rgba(0,0,0,.2);
        }
        .navbar-jornalzin .navbar-brand {
            font-family: 'UnifrakturMaguntia', cursive;
            font-size: 1.6rem;
            color: #f1f5f9 !important;
        }
        .navbar-jornalzin .nav-link { color: #94a3b8 !important; font-weight: 500; }
        .navbar-jornalzin .nav-link:hover { color: #f1f5f9 !important; }

        /* ── Botão dark mode ── */
        .btn-dark-mode {
            background: transparent;
            border: 1.5px solid #475569;
            color: #94a3b8;
            border-radius: 50px;
            padding: .32rem .7rem;
            font-size: 1rem;
            cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; gap: .3rem; line-height: 1;
        }
        .btn-dark-mode:hover { border-color: #818cf8; color: #818cf8; }

        /* ── Botão menu ── */
        .btn-menu {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-weight: 600; font-size: .88rem;
            border-radius: 50px; padding: .4rem 1rem;
            display: flex; align-items: center; gap: .5rem;
            cursor: pointer; transition: all .2s;
        }
        html.dark .btn-menu { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        .btn-menu:hover { background: #e2e8f0; color: #1e293b; }
        html.dark .btn-menu:hover { background: #334155; color: #f1f5f9; }

        /* ── Dropdown dark ── */
        html.dark .dropdown-menu {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .dropdown-item { color: #cbd5e1 !important; }
        html.dark .dropdown-item:hover { background: #334155 !important; color: #f1f5f9 !important; }
        html.dark .dropdown-divider { border-color: #334155 !important; }
        html.dark .dropdown-item-text { color: #64748b !important; }

        /* ── Botões nav guest ── */
        .btn-nav-login {
            border: 1.5px solid #94a3b8; color: #94a3b8;
            border-radius: 50px; padding: .35rem 1rem;
            font-size: .88rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .btn-nav-login:hover { border-color: #f1f5f9; color: #f1f5f9; }
        .btn-nav-cadastro {
            background: #4f46e5; color: #fff !important;
            border-radius: 50px; padding: .35rem 1rem;
            font-size: .88rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
            border: 1.5px solid transparent;
        }
        .btn-nav-cadastro:hover { background: #3730a3; }

        /* ── Dark mode: cards e textos ── */
        html.dark .post-card,
        html.dark .card {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.dark .post-title { color: #e2e8f0 !important; }
        html.dark .post-title:hover { color: #a5b4fc !important; }
        html.dark .home-title { color: #e2e8f0 !important; }
        html.dark .post-excerpt,
        html.dark .post-meta,
        html.dark .comment-text,
        html.dark .home-poll-label,
        html.dark .text-muted { color: var(--muted) !important; }

        /* Search inputs */
        html.dark .search-wrap input,
        html.dark .search-bar input {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.dark .search-wrap input::placeholder,
        html.dark .search-bar input::placeholder { color: var(--muted) !important; }

        /* Filtros */
        html.dark .filtro-link { color: var(--muted) !important; }
        html.dark .filtro-link:hover { background: #1e293b !important; color: #a5b4fc !important; }
        html.dark .filtro-link.active { background: var(--brand-dark) !important; color: #fff !important; }

        /* Day separator */
        html.dark .day-sep-badge { background: #334155 !important; color: #e2e8f0 !important; }
        html.dark .day-sep-line { background: #334155 !important; }

        /* Post footer */
        html.dark .post-footer { border-top-color: var(--border) !important; }
        html.dark .post-meta-sep { color: #334155 !important; }

        /* Botões like / comment */
        html.dark .btn-like,
        html.dark .btn-comment {
            background: #1a2744 !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        html.dark .btn-like:hover,
        html.dark .btn-like.liked {
            border-color: #f43f5e !important;
            background: #2d1b26 !important;
            color: #f43f5e !important;
        }
        html.dark .btn-comment:hover,
        html.dark .btn-comment.active {
            border-color: #a5b4fc !important;
            background: #1e1f3a !important;
            color: #a5b4fc !important;
        }

        /* Comments panel */
        html.dark .comments-panel {
            background: #1a2744 !important;
            border-top-color: var(--border) !important;
        }
        html.dark .comment-author { color: #e2e8f0 !important; }
        html.dark .comment-avatar { background: var(--brand-dark) !important; }
        html.dark .comment-form input,
        html.dark .comment-form textarea {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .comment-form input::placeholder,
        html.dark .comment-form textarea::placeholder { color: #64748b !important; }

        /* Poll */
        html.dark .home-poll-opt { border-color: #334155 !important; color: #e2e8f0 !important; }
        html.dark .home-poll-opt:hover { border-color: #a5b4fc !important; background: #1e1f3a !important; }
        html.dark .poll-res-bg { background: #0f172a !important; border-color: #334155 !important; }
        html.dark .poll-res-label { color: #cbd5e1 !important; }

        /* Post show page */
        html.dark .post-card { background: var(--surface) !important; border-color: var(--border) !important; }
        html.dark .post-title { color: var(--text) !important; }
        html.dark .post-text,
        html.dark .post-excerpt { color: var(--muted) !important; }
        html.dark .post-tipo.tipo-texto   { background: #1e3a5f !important; color: #93c5fd !important; }
        html.dark .post-tipo.tipo-imagem  { background: #4a1942 !important; color: #f9a8d4 !important; }
        html.dark .post-tipo.tipo-video   { background: #14532d !important; color: #86efac !important; }
        html.dark .post-tipo.tipo-enquete { background: #451a03 !important; color: #fcd34d !important; }
        html.dark .comments-card { background: var(--surface) !important; border-color: var(--border) !important; }
        html.dark .comments-header { color: var(--text) !important; border-color: var(--border) !important; }
        html.dark .comment-item { border-color: var(--border) !important; }
        html.dark .comment-text { color: var(--muted) !important; }
        html.dark .empty-comments { color: var(--muted) !important; }
        html.dark .comment-form-card { background: var(--surface) !important; border-color: var(--border) !important; }
        html.dark .comment-form-card h5 { color: var(--text) !important; }
        html.dark .btn-like,
        html.dark .btn-back { background: #1a2744 !important; border-color: #334155 !important; color: #94a3b8 !important; }
        html.dark .btn-back:hover { border-color: var(--brand-light) !important; color: var(--brand-light) !important; }

        /* Poll no show */
        html.dark .poll-opt { border-color: #334155 !important; color: #e2e8f0 !important; }
        html.dark .poll-opt:hover { border-color: var(--brand) !important; background: #1e1f3a !important; }
        html.dark .poll-res-bg { background: #0f172a !important; border-color: #334155 !important; }

        /* Perfil / usuarios */
        html.dark .perfil-header,
        html.dark .perfil-card,
        html.dark .user-detail-card,
        html.dark .users-card { background: var(--surface) !important; border-color: var(--border) !important; }
        html.dark .perfil-card-title { color: var(--text) !important; }
        html.dark .perfil-card-meta { color: var(--muted) !important; }
        html.dark .perfil-info h2 { color: var(--text) !important; }
        html.dark .perfil-posts-title { color: var(--text) !important; }
        html.dark .detail-value { color: var(--text) !important; }
        html.dark .detail-label { color: var(--muted) !important; }
        html.dark .detail-icon { background: #1e3a5f !important; color: var(--brand-light) !important; }
        html.dark .user-detail-body { border-color: var(--border) !important; }
        html.dark .detail-row { border-color: var(--border) !important; }

        /* Posts index */
        html.dark .card-title { color: var(--text) !important; }
        html.dark .card-head  { border-color: var(--border) !important; }
        html.dark .card-author{ background: var(--surface-2) !important; border-color: var(--border) !important; }
        html.dark .author-name{ color: var(--text) !important; }
        html.dark .author-date{ color: var(--muted) !important; }
        html.dark .card-body  { background: var(--surface) !important; }
        html.dark .card-actions { background: var(--surface-2) !important; border-color: var(--border) !important; }
        html.dark .card-divider { background: var(--border) !important; }
        html.dark .post-text  { color: var(--muted) !important; }
        html.dark .result-label { color: var(--text) !important; }
        html.dark .result-bar-bg { background: #0f172a !important; border-color: var(--border) !important; }
        html.dark .poll-total { color: var(--muted) !important; }

        /* Modais */
        html.dark .modal-content {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .modal-form label { color: #94a3b8 !important; }
        html.dark .modal-form .form-control {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .btn-outline-secondary { color: #94a3b8 !important; border-color: #475569 !important; }
        html.dark .btn-outline-secondary:hover { background: #334155 !important; color: #f1f5f9 !important; }

        /* Modal denúncia dark */
        html.dark .denuncia-box { background: var(--surface) !important; }
        html.dark .denuncia-box h5 { color: var(--text) !important; }
        html.dark .denuncia-box p  { color: var(--muted) !important; }
        html.dark .denuncia-opcao { border-color: var(--border) !important; color: var(--text) !important; }
        html.dark #modalDenuncia .denuncia-box,
        html.dark #modalDenunciaShow .denuncia-box { background: var(--surface) !important; }

        /* Paginação dark */
        html.dark .pagination {
            --bs-pagination-bg:                #1e293b !important;
            --bs-pagination-border-color:      #334155 !important;
            --bs-pagination-color:             #94a3b8 !important;
            --bs-pagination-hover-bg:          #334155 !important;
            --bs-pagination-hover-color:       #e2e8f0 !important;
            --bs-pagination-hover-border-color:#475569 !important;
            --bs-pagination-active-bg:         #4f46e5 !important;
            --bs-pagination-active-border-color:#4f46e5 !important;
            --bs-pagination-disabled-bg:       #1e293b !important;
            --bs-pagination-disabled-color:    #475569 !important;
            --bs-pagination-disabled-border-color:#334155 !important;
        }

        /* Carrossel dark */
        html.dark .carousel-thumbs { background: var(--surface-2) !important; border-color: var(--border) !important; }
        html.dark .carousel-thumb  { border-color: transparent !important; }
        html.dark .carousel-thumb.active { border-color: var(--brand-light) !important; }

        /* Instagram perfil dark */
        html.dark .ig-post-placeholder { background: linear-gradient(135deg, #1e3a5f, #1e293b) !important; }
        html.dark .ig-post-placeholder p { color: #94a3b8 !important; }
        html.dark .ig-username { color: var(--text) !important; }
        html.dark .ig-stats    { color: var(--text) !important; }
        html.dark .ig-stat a   { color: var(--text) !important; }
        html.dark .btn-settings { background: var(--surface-2) !important; border-color: var(--border) !important; color: var(--muted) !important; }
        html.dark .btn-settings:hover { border-color: var(--brand-light) !important; color: var(--brand-light) !important; }

        /* Footer */
        footer {
            padding: 20px;
            background: var(--surface-2);
            color: var(--muted);
            text-align: center;
            border-top: 1px solid var(--border);
            margin-top: auto;
        }

        /* Modais gradient header */
        .modal-grad-header {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #fff; border-radius: 16px 16px 0 0;
            border: none;
        }
        .modal-grad-header .btn-close { filter: invert(1); }
        .modal-form label { font-weight: 600; font-size: .85rem; color: #475569; text-transform: uppercase; letter-spacing: .04em; }
        .modal-form .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .95rem; padding: .6rem .9rem; }
        .modal-form .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .btn-modal-enviar { background: linear-gradient(135deg, #4f46e5, #3730a3); color: #fff; border: none; border-radius: 50px; padding: .6rem 1.6rem; font-weight: 700; font-size: .95rem; transition: all .2s; }
        .btn-modal-enviar:hover { transform: translateY(-1px); color: #fff; }
        .toast-msg {
    display: flex; align-items: center; gap: .75rem;
    background: #1e293b; color: #f1f5f9;
    border-radius: 14px; padding: .85rem 1.2rem;
    box-shadow: 0 8px 32px rgba(0,0,0,.35);
    font-size: .93rem; font-weight: 500;
    min-width: 280px; max-width: 380px;
    border-left: 4px solid #22c55e;
    animation: slideInToast .3s ease;
    position: relative; overflow: hidden;
}
.toast-msg.error   { border-left-color: #ef4444; }
.toast-msg.warning { border-left-color: #f59e0b; }
.toast-msg .toast-icon { font-size: 1.2rem; flex-shrink: 0; }
.toast-msg .toast-progress {
    position: absolute; bottom: 0; left: 0; height: 3px;
    background: #22c55e; animation: toastProgress 4s linear forwards;
}
.toast-msg.error   .toast-progress { background: #ef4444; }
.toast-msg.warning .toast-progress { background: #f59e0b; }
@keyframes slideInToast {
    from { opacity: 0; transform: translateX(60px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes toastProgress {
    from { width: 100%; }
    to   { width: 0%; }
}
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-jornalzin px-4">
    <a href="{{ route('home') }}" class="navbar-brand">Jornalzin</a>
    <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
            @auth
                @if(auth()->user()->tipo !== 'leitor')
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Posts</a></li>
                @endif
                @if(auth()->user()->tipo === 'administrador')
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuários</a></li>
                @endif
            @endauth
        </ul>
    </div>

    <button class="btn-dark-mode me-2" id="btnDarkMode" title="Alternar modo escuro" onclick="toggleDark()">
        <span id="darkIcon">🌙</span>
    </button>

    @auth
    <div class="dropdown ms-2">
        <button class="btn-menu dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span>☰</span>
            <span>{{ auth()->user()->nome ?? 'Usuário' }}</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:220px;border-radius:12px;border:1px solid #e2e8f0;">
            {{-- Perfil → redireciona para /u/{id} estilo Instagram --}}
            <li>
                <a class="dropdown-item py-2" href="{{ route('users.perfil', auth()->id()) }}">
                    👤 Perfil
                </a>
            </li>
            <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalContato">📩 Contato</a></li>
            @if(auth()->user()->tipo === 'leitor')
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalSolicitarEditor">✍️ Quero ser Editor</a></li>
            @endif
            @if(auth()->user()->tipo === 'administrador')
                @php $totalPendentes = \App\Models\SolicitacaoEditor::where('status', 'pendente')->count(); @endphp
                <li><hr class="dropdown-divider my-1"></li>
                <li><span class="dropdown-item-text text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;">Admin</span></li>
                <li><a class="dropdown-item py-2" href="{{ route('comments.index') }}">💬 Comentários</a></li>
                @php $totalDenuncias = \App\Models\Denuncia::where('lida', false)->count(); @endphp
<li>
    <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="{{ route('admin.denuncias') }}">
        <span>🚩 Denúncias</span>
        @if($totalDenuncias > 0)
            <span style="background:#ef4444;color:#fff;border-radius:50px;padding:.1rem .55rem;font-size:.75rem;font-weight:700;">{{ $totalDenuncias }}</span>
        @endif
    </a>
</li>
                <li>
                    <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="{{ route('admin.solicitacoes') }}">
                        <span>📋 Solicitações de Editor</span>
                        @if($totalPendentes > 0)
                            <span style="background:#ef4444;color:#fff;border-radius:50px;padding:.1rem .55rem;font-size:.75rem;font-weight:700;">{{ $totalPendentes }}</span>
                        @endif
                    </a>
                </li>
            @endif
            <li><hr class="dropdown-divider my-1"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger fw-bold">🚪 Sair</button>
                </form>
            </li>
        </ul>
    </div>
    @endauth

    @guest
    <div class="ms-2 d-flex align-items: center gap-2">
        <a href="{{ route('login') }}" class="btn-nav-login">Entrar</a>
        <a href="{{ url('/users/create') }}" class="btn-nav-cadastro">Cadastro</a>
    </div>
    @endguest
</nav>

<!-- MODAL CONTATO -->
<div class="modal fade" id="modalContato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:860px;width:95%;">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
            <div class="modal-header modal-grad-header">
                <h5 class="modal-title fw-bold">📩 Fale Conosco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form class="modal-form" onsubmit="enviarContato(event)">
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" class="form-control mt-1" value="{{ auth()->user()->nome ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label>E-mail</label>
                        <input type="email" class="form-control mt-1" value="{{ auth()->user()->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Assunto</label>
                        <input type="text" class="form-control mt-1" placeholder="Assunto da mensagem" required>
                    </div>
                    <div class="mb-4">
                        <label>Mensagem</label>
                        <textarea class="form-control mt-1" rows="5" placeholder="Escreva sua mensagem..." required style="resize:none;"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-enviar">📤 Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Toast container -->
<div id="toast-container" style="
    position: fixed; bottom: 1.5rem; right: 1.5rem;
    z-index: 99998; display: flex; flex-direction: column; gap: .6rem;
"></div>
<!-- MODAL SOLICITAR EDITOR -->
@auth
@if(auth()->user()->tipo === 'leitor')
<div class="modal fade" id="modalSolicitarEditor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:700px;width:95%;">
        <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
            <div class="modal-header modal-grad-header">
                <h5 class="modal-title fw-bold">✍️ Solicitar acesso de Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                @if(session('success_solicitacao'))
                    <div class="alert alert-success">{{ session('success_solicitacao') }}</div>
                @endif
                @if(session('erro_solicitacao'))
                    <div class="alert alert-warning">{{ session('erro_solicitacao') }}</div>
                @endif
                <form class="modal-form" method="POST" action="{{ route('editor.solicitar') }}">
                    @csrf
                    <div class="mb-3">
                        <label>Por que quer ser Editor?</label>
                        <textarea name="motivo" class="form-control mt-1" rows="5"
                                  placeholder="Explique seu interesse..." required minlength="20" style="resize:none;"></textarea>
                    </div>
                    <div class="mb-4">
                        <label>Experiência com escrita/jornalismo (opcional)</label>
                        <textarea name="experiencia" class="form-control mt-1" rows="3"
                                  placeholder="Ex: escrevo para blogs..." style="resize:none;"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-modal-enviar">📤 Enviar Solicitação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

<div class="page-content container mt-4">
    @yield('conteudo')
</div>

<footer>© {{ date('Y') }} Jornalzin - Projeto TCC</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
    const html = document.getElementById('html-root');
    const icon = document.getElementById('darkIcon');

    // Aplica dark mode salvo ANTES do render para evitar flash
    if (localStorage.getItem('dark') === '1') {
        html.classList.add('dark');
        if (icon) icon.textContent = '☀️';
    }

    function toggleDark() {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        if (icon) icon.textContent = isDark ? '☀️' : '🌙';
        localStorage.setItem('dark', isDark ? '1' : '0');
    }

    function enviarContato(e) {
        e.preventDefault();
        bootstrap.Modal.getInstance(document.getElementById('modalContato')).hide();
        alert('✅ Mensagem enviada com sucesso!');
    }
    /* ── Carrossel Home ── */
const _carouselState = {};

function moverCarousel(id, dir) {
    const el    = document.getElementById(id);
    const track = el.querySelector('.home-carousel-track');
    const total = el.querySelectorAll('.home-carousel-slide').length;

    if (!_carouselState[id]) _carouselState[id] = 0;
    _carouselState[id] = (_carouselState[id] + dir + total) % total;
    _atualizarCarousel(id, el, track, total);
}

function irParaSlide(id, idx) {
    const el    = document.getElementById(id);
    const track = el.querySelector('.home-carousel-track');
    const total = el.querySelectorAll('.home-carousel-slide').length;
    _carouselState[id] = idx;
    _atualizarCarousel(id, el, track, total);
}

function _atualizarCarousel(id, el, track, total) {
    const idx = _carouselState[id];
    track.style.transform = `translateX(-${idx * 100}%)`;

    // dots
    el.querySelectorAll('.hc-dot').forEach((d, i) =>
        d.classList.toggle('active', i === idx));

    // contador
    const counter = el.querySelector('.hc-current');
    if (counter) counter.textContent = idx + 1;
}
function showToast(msg, type = 'success') {
    const icons = { success: '✅', error: '❌', warning: '⚠️' };
    const el = document.createElement('div');
    el.className = `toast-msg ${type !== 'success' ? type : ''}`;
    el.innerHTML = `
        <span class="toast-icon">${icons[type] || '✅'}</span>
        <span>${msg}</span>
        <div class="toast-progress"></div>
    `;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateX(60px)';
        el.style.transition = 'all .3s ease';
        setTimeout(() => el.remove(), 300);
    }, 4000);
}
@if(session('success'))
    showToast("{{ session('success') }}", 'success');
@endif
@if(session('error'))
    showToast("{{ session('error') }}", 'error');
@endif
@if(session('warning'))
    showToast("{{ session('warning') }}", 'warning');
@endif

</script>
</body>
</html>