<!DOCTYPE html>
<html lang="pt-br" id="html-root">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jornalzin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* ── Cursor glow ── */
        #cursor-glow {
            position: fixed;
            width: 160px; height: 160px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 99999;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(99,102,241,.25) 0%, transparent 70%);
            transition: opacity .4s;
            opacity: 0;
        }
        body:hover #cursor-glow { opacity: 1; }
        html.dark #cursor-glow {
            background: radial-gradient(circle, rgba(129,140,248,.3) 0%, transparent 70%);
        }

        /* ══ LIGHT MODE ══ */
        :root {
            --bg:         #f1f5f9;
            --surface:    #ffffff;
            --surface-2:  #f8fafc;
            --border:     #e2e8f0;
            --text:       #1e293b;
            --muted:      #64748b;
            --nav-bg:     #1e293b;
            --nav-border: #334155;
        }

        /* ══ DARK MODE ══ */
        html.dark {
            --bg:        #0f172a;
            --surface:   #1e293b;
            --surface-2: #1a2744;
            --border:    #334155;
            --text:      #e2e8f0;
            --muted:     #94a3b8;
            --nav-bg:    #0b1120;
            --nav-border:#1e293b;
        }

        *, *::before, *::after {
            transition: background-color .3s ease, border-color .3s ease, color .2s ease, box-shadow .3s ease;
        }

        html, body {
            background-color: var(--bg) !important;
            color: var(--text) !important;
        }

        /* ── Navbar ── */
        .navbar-jornalzin {
            background: var(--nav-bg) !important;
            border-bottom: 1px solid var(--nav-border);
            box-shadow: 0 1px 8px rgba(0,0,0,.2);
        }
        .navbar-jornalzin .navbar-brand { font-weight: 800; font-size: 1.2rem; color: #f1f5f9 !important; }
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
            display: flex; align-items: center; gap: .3rem;
            line-height: 1;
        }
        .btn-dark-mode:hover { border-color: #818cf8; color: #818cf8; }

        /* ── Menu usuário ── */
        .btn-menu {
            background: #f1f5f9; border: 1px solid #e2e8f0; color: #334155;
            font-weight: 600; font-size: .88rem; border-radius: 50px;
            padding: .4rem 1rem; display: flex; align-items: center; gap: .5rem;
            cursor: pointer; transition: all .2s;
        }
        html.dark .btn-menu { background: #1e293b; border-color: #334155; color: #e2e8f0; }
        .btn-menu:hover { background: #e2e8f0; color: #1e293b; }
        html.dark .btn-menu:hover { background: #334155; color: #f1f5f9; }

        /* ── Dropdown ── */
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

        /* ══ DARK — elementos da home ══ */

        /* Cards de post */
        html.dark .post-card,
        html.dark .card {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }

        /* Títulos */
        html.dark .post-title { color: #e2e8f0 !important; }
        html.dark .post-title:hover { color: #818cf8 !important; }
        html.dark .home-title { color: #e2e8f0 !important; }

        /* Textos secundários */
        html.dark .post-excerpt,
        html.dark .post-meta,
        html.dark .comment-text,
        html.dark .home-poll-label,
        html.dark .text-muted { color: var(--muted) !important; }

        /* Barra de pesquisa */
        html.dark .search-wrap input {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text) !important;
        }
        html.dark .search-wrap input::placeholder { color: var(--muted) !important; }

        /* Filtros de ordenação */
        html.dark .filtro-link { color: var(--muted) !important; }
        html.dark .filtro-link:hover { background: #1e293b !important; color: #818cf8 !important; }
        html.dark .filtro-link.active { background: #4f46e5 !important; color: #fff !important; }

        /* Separador de dia */
        html.dark .day-sep-badge { background: #334155 !important; color: #e2e8f0 !important; }
        html.dark .day-sep-line  { background: #334155 !important; }

        /* Footer dos cards */
        html.dark .post-footer { border-top-color: var(--border) !important; }
        html.dark .post-meta-sep { color: #334155 !important; }

        /* Botões like/comentário */
        html.dark .btn-like,
        html.dark .btn-comment {
            background: #1a2744 !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        html.dark .btn-like:hover   { border-color: #f43f5e !important; background: #2d1b26 !important; color: #f43f5e !important; }
        html.dark .btn-like.liked   { border-color: #f43f5e !important; background: #2d1b26 !important; color: #f43f5e !important; }
        html.dark .btn-comment:hover  { border-color: #818cf8 !important; background: #1e1f3a !important; color: #818cf8 !important; }
        html.dark .btn-comment.active { border-color: #818cf8 !important; background: #1e1f3a !important; color: #818cf8 !important; }

        /* Painel de comentários */
        html.dark .comments-panel {
            background: #1a2744 !important;
            border-top-color: var(--border) !important;
        }
        html.dark .comment-author { color: #e2e8f0 !important; }
        html.dark .comment-avatar { background: #4f46e5 !important; }
        html.dark .comment-form input {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .comment-form input::placeholder { color: #64748b !important; }

        /* Enquete/poll */
        html.dark .home-poll-opt {
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .home-poll-opt:hover { border-color: #818cf8 !important; background: #1e1f3a !important; }
        html.dark .poll-res-bg  { background: #0f172a !important; border-color: #334155 !important; }
        html.dark .poll-res-label { color: #cbd5e1 !important; }

        /* ── Footer ── */
        footer {
            margin-top: 40px; padding: 20px;
            background: var(--surface-2);
            color: var(--muted);
            text-align: center;
            border-top: 1px solid var(--border);
        }

        /* ── Modais ── */
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

        .modal-grad-header {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #fff; border-radius: 16px 16px 0 0; border: none;
        }
        .modal-grad-header .btn-close { filter: invert(1); }
        .modal-form label { font-weight: 600; font-size: .85rem; color: #475569; text-transform: uppercase; letter-spacing: .04em; }
        .modal-form .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .95rem; padding: .6rem .9rem; }
        .modal-form .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .btn-modal-enviar {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #fff; border: none; border-radius: 50px;
            padding: .6rem 1.6rem; font-weight: 700; font-size: .95rem; transition: all .2s;
        }
        .btn-modal-enviar:hover { transform: translateY(-1px); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Cursor glow -->
<div id="cursor-glow"></div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-jornalzin px-4">
    <a href="{{ route('home') }}" class="navbar-brand">📰 Jornalzin</a>
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
            <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">👤 Perfil</a></li>
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
    <div class="ms-2 d-flex align-items-center gap-2">
        <a href="{{ route('login') }}" class="btn-nav-login">Entrar</a>
        <a href="{{ url('/users/create') }}" class="btn-nav-cadastro">Cadastro</a>
    </div>
    @endguest
</nav>

<!-- MODAL CONTATO -->
<div class="modal fade" id="modalContato" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                        <textarea class="form-control mt-1" rows="4" placeholder="Escreva sua mensagem..." required></textarea>
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

<!-- MODAL SOLICITAR EDITOR -->
@auth
@if(auth()->user()->tipo === 'leitor')
<div class="modal fade" id="modalSolicitarEditor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                        <textarea name="motivo" class="form-control mt-1" rows="4"
                                  placeholder="Explique seu interesse..." required minlength="20"></textarea>
                    </div>
                    <div class="mb-4">
                        <label>Experiência com escrita/jornalismo (opcional)</label>
                        <textarea name="experiencia" class="form-control mt-1" rows="2"
                                  placeholder="Ex: escrevo para blogs..."></textarea>
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

<div class="container mt-4">
    @yield('conteudo')
</div>

<footer>© {{ date('Y') }} Jornalzin - Projeto TCC</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
    const html = document.getElementById('html-root');
    const icon = document.getElementById('darkIcon');

    // Aplica dark antes do paint para evitar flash
    if (localStorage.getItem('dark') === '1') {
        html.classList.add('dark');
        icon.textContent = '☀️';
    }

    function toggleDark() {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        icon.textContent = isDark ? '☀️' : '🌙';
        localStorage.setItem('dark', isDark ? '1' : '0');
    }

    /* ── Cursor glow ── */
    const glow = document.getElementById('cursor-glow');
    let mx = 0, my = 0, cx = 0, cy = 0;
    document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
    function animateGlow() {
        cx += (mx - cx) * 0.10;
        cy += (my - cy) * 0.10;
        glow.style.left = cx + 'px';
        glow.style.top  = cy + 'px';
        requestAnimationFrame(animateGlow);
    }
    animateGlow();

    function enviarContato(e) {
        e.preventDefault();
        bootstrap.Modal.getInstance(document.getElementById('modalContato')).hide();
        alert('✅ Mensagem enviada com sucesso!');
    }
</script>
</body>
</html>
