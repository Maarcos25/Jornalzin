@extends('layouts.autenticacao')

@section('conteudo')
<style>
    /* Estilos específicos para a tela de login */
    .login-header h2 { font-weight: 800; color: #333; margin-bottom: 5px; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #6c757d; text-transform: uppercase; margin-bottom: 8px; }
    .form-control { border-radius: 10px; padding: 12px; border: 1px solid #e0e0e0; transition: 0.3s; }
    .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }

    .btn-entrar { border-radius: 10px; padding: 12px; font-weight: 600; transition: 0.3s; }
    .btn-entrar:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2); }

    .btn-create { border-radius: 50px; font-weight: 500; font-size: 0.9rem; }

    .password-wrapper { position: relative; }
    .password-toggle {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        cursor: pointer; opacity: 0.6; transition: 0.2s; z-index: 10;
    }
    .password-toggle:hover { opacity: 1; }

    .forgot-link { font-size: 0.85rem; text-decoration: none; color: #6c757d; transition: 0.2s; }
    .forgot-link:hover { color: #0d6efd; text-decoration: underline; }
</style>

<div class="login-header text-center mb-4">
    <a href="{{ route('home') }}" class="text-decoration-none d-block">
        <h2><span>📰</span> Jornalzin</h2>
    </a>
    <p class="text-muted small">Insira seu e-mail e senha para continuar</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 10px;">
        <small>⚠️ {{ $errors->first() }}</small>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control"
               placeholder="seu@email.com" value="{{ old('email') }}" required autofocus>
    </div>

    <div class="mb-4">
        <label class="form-label">Senha</label>
        <div class="password-wrapper">
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="••••••••" required>
            <span class="password-toggle" onclick="toggleSenha()">
                <span id="eyeIcon">👁️</span>
            </span>
        </div>
    </div>

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary btn-entrar">
            Entrar no Sistema
        </button>
    </div>

    <div class="text-center">
        <p class="text-muted small mb-2">Ainda não tem acesso?</p>
        <a href="{{ url('/users/create') }}" class="btn btn-outline-primary btn-create px-4">
            Criar conta
        </a>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('password.request') }}" class="forgot-link">
            Esqueceu sua senha?
        </a>
    </div>
</form>

<script>
    function toggleSenha() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = '🙈'; // Muda o emoji se quiser
        } else {
            input.type = 'password';
            icon.textContent = '👁️';
        }
    }
</script>
@endsection
