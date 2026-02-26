<!DOCTYPE html>
<html>
    <head>
        <title>Jornalzin</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <nav>
            <a href="/">Home</a> |
            <a href="{{ route('users.index') }}">Usuários</a>
        </nav>
        <hr>

        @yield('conteudo')
    </body>
</html>
