<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Jornalzin</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand" href="/">
                📰 Jornalzin
            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.index') }}">
                            Usuários
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('comments.index') }}">
                            Comentários
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            Posts
                        </a>
                    </li>

                    @guest
                        <a href="{{ route('login') }}">Entrar</a>
                    @endguest

                    @auth
                        <a href="#">Sair</a>
                    @endauth

                </ul>
            </div>

        </div>
    </nav>


    <!-- CONTEÚDO -->
    <div class="container mt-4">

        @yield('conteudo')

    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
