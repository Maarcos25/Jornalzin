<!DOCTYPE html>
<html>
<head>
    <title>Jornalzin</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="mb-3">
        <!-- Links principais -->
        <a href="/" class="me-2">Home</a> |
        <a href="{{ route('users.index') }}" class="mx-2">Usuários</a> |
        <a href="{{ route('comments.index') }}" class="mx-2">Comentários</a> |
        <a href="{{ route('posts.index') }}" class="mx-2">Posts</a>

        <!-- Links de login/logout -->
        @guest
            | <a href="{{ route('login') }}" class="mx-2">Entrar</a>
            <a href="{{ route('register') }}" class="mx-2">Criar conta</a>
        @else
            | Olá, {{ Auth::user()->nome }}
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline ms-1">Sair</button>
            </form>
        @endguest
    </nav>
    <hr>

    <!-- Aqui entra o conteúdo de cada página -->
    @yield('conteudo')

</body>
</html>
