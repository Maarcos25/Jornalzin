<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jornalzin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; }

        .navbar-jornalzin {
    background: #1e293b;
    border-bottom: 1px solid #334155;
    box-shadow: 0 1px 8px rgba(0,0,0,.15);
}
.navbar-jornalzin .navbar-brand {
    font-weight: 800;
    font-size: 1.2rem;
    color: #f1f5f9 !important;
}
.navbar-jornalzin .nav-link {
    color: #94a3b8 !important;
    font-weight: 500;
}
.navbar-jornalzin .nav-link:hover {
    color: #f1f5f9 !important;
}
        .btn-menu {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-weight: 600;
            font-size: .88rem;
            border-radius: 50px;
            padding: .4rem 1rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            transition: all .2s;
        }
        .btn-menu:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        footer {
            margin-top: 40px;
            padding: 20px;
            background: #e2e8f0;
            color: #475569;
            text-align: center;
            border-top: 1px solid #cbd5e1;
        }

        /* Modal contato */
        .contato-modal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,.12);
        }
        .contato-modal .modal-header {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #fff;
            border-radius: 16px 16px 0 0;
            border: none;
        }
        .contato-modal .modal-header .btn-close {
            filter: invert(1);
        }
        .contato-form label {
            font-weight: 600;
            font-size: .85rem;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .contato-form .form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .95rem;
            padding: .6rem .9rem;
        }
        .contato-form .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }
        .btn-enviar {
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: .6rem 1.6rem;
            font-weight: 700;
            font-size: .95rem;
            box-shadow: 0 4px 14px rgba(79,70,229,.3);
            transition: all .2s;
        }
        .btn-enviar:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79,70,229,.4);
            color: #fff;
        }
    </style>
    @stack('scripts')
    @stack('styles')
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-jornalzin px-4">

        <a href="{{ route('home') }}" class="navbar-brand">
            📰 Jornalzin
        </a>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">Usuários</a>
                </li>
            </ul>
        </div>

        {{-- Menu ☰ lado direito --}}
        @auth
        <div class="dropdown ms-auto">
            <button class="btn-menu dropdown-toggle"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span>☰</span>
                <span>{{ auth()->user()->nome ?? 'Usuário' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:210px; border-radius:12px; border:1px solid #e2e8f0;">

                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                        👤 Perfil
                    </a>
                </li>

                @if(auth()->user()->tipo === 'administrador')
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><span class="dropdown-item-text text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;">Admin</span></li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('comments.index') }}">
                            💬 Comentários
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#modalContato">
                            📩 Contato
                        </a>
                    </li>
                @endif

                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger fw-600">
                            🚪 Sair
                        </button>
                    </form>
                </li>

            </ul>
        </div>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm ms-auto">
                Entrar
            </a>
        @endguest

    </nav>

    <!-- MODAL CONTATO -->
    <div class="modal fade contato-modal" id="modalContato" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">📩 Fale Conosco</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form class="contato-form" onsubmit="enviarContato(event)">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" class="form-control mt-1" placeholder="Seu nome"
                                   value="{{ auth()->user()->nome ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input type="email" class="form-control mt-1" placeholder="Seu e-mail"
                                   value="{{ auth()->user()->email ?? '' }}" required>
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
                            <button type="submit" class="btn-enviar">📤 Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTEÚDO -->
    <div class="container mt-4">
        @yield('conteudo')
    </div>

    <!-- FOOTER -->
    <footer>
        © {{ date('Y') }} Jornalzin - Projeto TCC
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enviarContato(e) {
            e.preventDefault();
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalContato'));
            modal.hide();
            alert('✅ Mensagem enviada com sucesso!');
        }
    </script>

</body>
</html>
