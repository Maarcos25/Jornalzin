<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Jornalzin - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            padding: 40px 0;
        }

        .auth-card {
            width: 100%;
            max-width: 700px;
            margin: auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }

        </style>
</head>

<body>

    <div class="card auth-card">

        @yield('conteudo')

    </div>

</body>

</html>
