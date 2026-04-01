    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Jornalzin</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            body {
                background-color: #f5f5f5;
            }

            .navbar-brand {
                font-weight: bold;
                font-size: 22px;
            }

            footer {
                margin-top: 40px;
                padding: 20px;
                background: #212529;
                color: white;
                text-align: center;
            }
            </style>

            @stack('scripts')

            @stack('styles')
    </head>

    <body>

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">

            <a href="{{ route('home') }}" class="navbar-brand">
                📰 Jornalzin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

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

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('comments.index') }}">
                            💬 Comentários
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">

                    @auth
                        <span class="text-white me-3">
                            Bem-vindo, {{ auth()->user()->nome ?? 'Usuário' }}
                        </span>

                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-light btn-sm me-2">
                            👤 Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center m-0">
                            @csrf
                            <button class="btn btn-danger btn-sm ms-2">
                                Sair
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                            Entrar
                        </a>
                    @endguest

                </div>

            </div>
        </nav>

        <!-- CONTEÚDO -->
        <div class="container mt-4">
            @yield('conteudo')
        </div>

        <!-- FOOTER -->
        <footer>
            © {{ date('Y') }} Jornalzin - Projeto TCC
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    </body>
    </html>
