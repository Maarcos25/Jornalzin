<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Jornalzin - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('scripts')
    <script>
        function toggleSenha() {
            let input = document.getElementById("password");

            if (!input) {
                console.log("INPUT NÃO ENCONTRADO");
                return;
            }

            input.type = input.type === "password" ? "text" : "password";
        }
        </script>
    <style>


        body {
            font-size:17px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;     /* centraliza vertical */
            justify-content: center; /* centraliza horizontal */

            padding: 20px;
        }

        .auth-card {
    width: 100%;
    max-width: 900px;   /* largura máxima continua igual */
    padding: 50px 40px; /* mais espaçamento interno vertical */
    min-height: 600px;  /* garante altura mínima */
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    justify-content: center; /* centraliza verticalmente o conteúdo dentro do card */
}

        .logo {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        </style>
</head>

<body>

    <div class="card auth-card">

        @yield('conteudo')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
