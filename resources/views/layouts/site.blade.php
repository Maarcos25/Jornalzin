<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jornalzin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; }
        .navbar-jornalzin { background: #1e293b; border-bottom: 1px solid #334155; box-shadow: 0 1px 8px rgba(0,0,0,.15); }
        .navbar-jornalzin .navbar-brand { font-weight: 800; font-size: 1.2rem; color: #f1f5f9 !important; }
        .navbar-jornalzin .nav-link { color: #94a3b8 !important; font-weight: 500; }
        .navbar-jornalzin .nav-link:hover { color: #f1f5f9 !important; }
        .btn-menu {
            background: #f1f5f9; border: 1px solid #e2e8f0; color: #334155;
            font-weight: 600; font-size: .88rem; border-radius: 50px;
            padding: .4rem 1rem; display: flex; align-items: center; gap: .5rem; transition: all .2s;
        }
        .btn-menu:hover { background: #e2e8f0; color: #1e293b; }
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
        footer { margin-top: 40px; padding: 20px; background: #e2e8f0; color: #475569; text-align: center; border-top: 1px solid #cbd5e1; }
        .modal-grad-header { background: linear-gradient(135deg, #4f46e5, #3730a3); color: #fff; border-radius: 16px 16px 0 0; border: none; }
        .modal-grad-header .btn-close { filter: invert(1); }
        .modal-form label { font-weight: 600; font-size: .85rem; color: #475569; text-transform: uppercase; letter-spacing: .04em; }
        .modal-form .form-control { border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: .95rem; padding: .6rem .9rem; }
        .modal-form .form-control:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
        .btn-modal-enviar { background: linear-gradient(135deg, #4f46e5, #3730a3); color: #fff; border: none; border-radius: 50px; padding: .6rem 1.6rem; font-weight: 700; font-size: .95rem; transition: all .2s; }
        .btn-modal-enviar:hover { transform: translateY(-1px); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-jornalzin px-4">
        <a href="{{ route('home') }}" class="navbar-brand">📰 Jornalzin</a>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Posts</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Usuários</a></li>
            </ul>
        </div>

        @auth
        <div class="dropdown ms-auto">
            <button class="btn-menu dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span>☰</span>
                <span>{{ auth()->user()->nome ?? 'Usuário' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:220px;border-radius:12px;border:1px solid #e2e8f0;">

                {{-- Perfil sempre visível --}}
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">👤 Perfil</a></li>

                {{-- Leitor: solicitar editor --}}
                @if(auth()->user()->tipo === 'leitor')
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalSolicitarEditor">✍️ Quero ser Editor</a></li>
                @endif

                {{-- Admin: painel --}}
                @if(auth()->user()->tipo === 'administrador')
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><span class="dropdown-item-text text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;">Admin</span></li>
                    <li><a class="dropdown-item py-2" href="{{ route('comments.index') }}">💬 Comentários</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('admin.solicitacoes') }}">📋 Solicitações de Editor</a></li>
                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalContato">📩 Contato</a></li>
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
        <div class="ms-auto d-flex align-items-center gap-2">
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
                                      placeholder="Explique seu interesse em criar conteúdo para o Jornalzin..."
                                      required minlength="20"></textarea>
                        </div>
                        <div class="mb-4">
                            <label>Experiência com escrita/jornalismo (opcional)</label>
                            <textarea name="experiencia" class="form-control mt-1" rows="2"
                                      placeholder="Ex: escrevo para blogs, já trabalhei em jornal escolar..."></textarea>
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
        function enviarContato(e) {
            e.preventDefault();
            bootstrap.Modal.getInstance(document.getElementById('modalContato')).hide();
            alert('✅ Mensagem enviada com sucesso!');
        }
    </script>
</body>
</html>
