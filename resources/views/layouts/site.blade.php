<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
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
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="/">📰 Jornalzin</a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">Usuários</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('comments.index') }}">Comentários</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-light me-2" href="{{ route('login') }}">
                                Entrar
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item">
                            <a class="btn btn-outline-light" href="{{ route('profile.edit') }}">
                                Perfil
                            </a>
                        </li>

                        <li class="nav-item ms-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Sair
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
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
