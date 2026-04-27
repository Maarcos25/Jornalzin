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
            // Aplica dark mode ANTES do render para evitar flash
    if (localStorage.getItem('dark') === '1') {
        document.documentElement.classList.add('dark');
    }
    
        </script>
    <style>
html.dark body {
    background: linear-gradient(135deg, #0b1120, #1e293b);
}
html.dark .auth-card {
    background: #1e293b !important;
    color: #e2e8f0 !important;
}
html.dark .form-control {
    background: #0f172a !important;
    border-color: #334155 !important;
    color: #e2e8f0 !important;
}
html.dark .form-label { color: #94a3b8 !important; }
html.dark .text-muted { color: #94a3b8 !important; }
html.dark .btn-google {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #e2e8f0 !important;
}
html.dark .login-header h2 { color: #e2e8f0 !important; }
html.dark .forgot-link { color: #94a3b8 !important; }
html.dark .divider span { color: #64748b !important; }
html.dark .divider hr { border-color: #334155 !important; }

        body {
            font-size:17px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            min-height: 100vh;
            display: flex;
            align-items: center;     /* centraliza vertical */
            justify-content: center; /* centraliza horizontal */

            padding: 20px;
        }

/* Sobrescreve o layout pai para centralizar */
.auth-card {
    max-width: 850px !important;
    width: 100% !important;
    padding: 5rem 3rem !important;  /* era 2rem */
    min-height: unset !important;
    background: #ffffff !important;
    box-shadow: 0 8px 32px rgba(0,0,0,.2) !important;
    border: none !important;
    border-radius: 18px !important;
}

.wrap {
    max-width: 100%;
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
    <button onclick="toggleDark()" style="position:fixed;top:1rem;right:1rem;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:50px;padding:.32rem .7rem;font-size:1rem;cursor:pointer;">
    <span id="darkIcon">🌙</span>
    <script>
    const icon = document.getElementById('darkIcon');
    if (localStorage.getItem('dark') === '1') icon.textContent = '☀️';
    
    function toggleDark() {
        document.documentElement.classList.toggle('dark');
        const isDark = document.documentElement.classList.contains('dark');
        icon.textContent = isDark ? '☀️' : '🌙';
        localStorage.setItem('dark', isDark ? '1' : '0');
    }
</script>
</button>

    <div class="card auth-card">

        @yield('conteudo')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
